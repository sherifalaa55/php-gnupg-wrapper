<?php

namespace Tugrul\Pgp;

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

    public function generateKey($name, $email, $expire = null)
    {
        $config = [
            '%no-protection', // keep keys without passphrase
            'Key-Type: default',
            'Subkey-Type: default',
            'Name-Real: ' . $name,
            'Name-Email: ' . $email,
            'Expire-Date: ' . (empty($expire) ? '0' : date('Ymd\THis', $expire)),
            '%commit'
        ];

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

}