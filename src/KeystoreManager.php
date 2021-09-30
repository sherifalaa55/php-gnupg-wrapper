<?php

namespace Sherifai\Pgp;

class KeystoreManager
{
    /**
     * @var string
     */
    protected $keystoreDir;

    /**
     * @var string
     */
    protected $commandResult;

    /**
     * @var string
     */
    protected $commandError;

    /**
     * @var string
     */
    protected $configuration;

    /**
     * KeystoreManager constructor.
     * @param string $keystoreDir
     */
    public function __construct($keystoreDir)
    {
        $this->keystoreDir = $keystoreDir;
    }

    public function generateKeyPair($name, $email, $passphrase = null, $expire = null)
    {
        // generate keypair
        $this->generateKey($name, $email, $passphrase, $expire);

        // $kp = new KeyPair;
        // $kp->setPublicKey($this->exportPublicKey($name));
        // $kp->setPrivateKey($this->exportPrivateKey($name));

        return $this->findKeyPair($name);
    }

    public function generateKey($name, $email, $passphrase = null, $expire = null)
    {
        $config = [
            'Key-Type: default',
            'Subkey-Type: default',
            'Name-Real: ' . $name,
            'Name-Email: ' . $email,
            'Expire-Date: ' . (empty($expire) ? '0' : date('Ymd\THis', $expire)),
        ];

        if (empty($passphrase)) {
            array_unshift($config, '%no-protection');
        } else {
            $config[] = 'Passphrase: ' . $passphrase;
        }

        $config[] = '%commit';

        $process = proc_open('gpg --batch --generate-key', [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w'],
        ], $pipes, dirname($this->keystoreDir), [
            'GNUPGHOME' => $this->keystoreDir
        ]);

        if (!is_resource($process)) {
            throw new \Exception('gpg process couldn\'t start');
        }

        $this->configuration = implode(PHP_EOL, $config);

        fwrite($pipes[0], $this->configuration);
        fclose($pipes[0]);

        $this->commandResult = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $this->commandError = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        return proc_close($process);

    }

    public function exportPublicKey($userName)
    {
        $process = proc_open("gpg --export -a '{$userName}'", [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w'],
        ], $pipes, dirname($this->keystoreDir), [
            'GNUPGHOME' => $this->keystoreDir
        ]);

        $public = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        return $public; 
    }

    public function exportPrivateKey($userName)
    {
        $process = proc_open("gpg --export-secret-key -a '{$userName}'", [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w'],
        ], $pipes, dirname($this->keystoreDir), [
            'GNUPGHOME' => $this->keystoreDir
        ]);

        $privateKey = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        return $privateKey; 
    }

    /**
     * @return string
     */
    public function getCommandResult()
    {
        return $this->commandResult;
    }

    /**
     * @return string
     */
    public function getCommandError()
    {
        return $this->commandError;
    }

    /**
     * @return string
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return array
     */
    public function listKeys()
    {
        $prevEnv = getenv('GNUPGHOME');
        putenv('GNUPGHOME=' . $this->keystoreDir);

        // empty string means list all keys
        $info = gnupg_keyinfo(gnupg_init(), '');

        if ($prevEnv !== false) {
            putenv('GNUPGHOME=' . $prevEnv);
        } else {
            putenv('GNUPGHOME');
        }

        return $info;
    }

    public function findKeyPair($username)
    {
        $keys = $this->listKeys();
        $subkey = null;
        $selectedKey = null;
        foreach ($keys as $key) {
            if ($key["uids"][0]["name"] == $username) {
                foreach ($key["subkeys"] as $sub) {
                    if ($sub['can_encrypt'] == true) {
                        $selectedKey = $key;
                        $subkey = $sub;
                        break;
                    }
                }
            }
        }
        
        $kp = new KeyPair;
        $kp->setPublicKey($this->exportPublicKey($username));
        $kp->setPrivateKey($this->exportPrivateKey($username));
        $kp->setFingerPrint($subkey["fingerprint"]);
        return $kp;
    }
}