<?php


namespace Tugrul\Pgp;


class GnupgWrapper
{

    protected $handle;

    /**
     * @var false|string
     */
    protected $prevKeystoreDir;

    /**
     * GnupgWrapper constructor.
     * @param string $keystoreDir
     */
    public function __construct($keystoreDir)
    {
        $this->prevKeystoreDir = getenv('GNUPGHOME');
        putenv('GNUPGHOME=' . $keystoreDir);

        $this->handle = gnupg_init();
    }

    public function __destruct()
    {
        gnupg_cleardecryptkeys($this->handle);
        gnupg_clearencryptkeys($this->handle);

        if ($this->prevKeystoreDir !== false) {
            putenv('GNUPGHOME=' . $this->prevKeystoreDir);
        } else {
            putenv('GNUPGHOME');
        }
    }

    public function addDecryptKey($fingerprint)
    {
        return gnupg_adddecryptkey($this->handle, $fingerprint);
    }

    public function addEncryptKey($fingerprint, $passphrase = null)
    {
        return gnupg_addencryptkey($this->handle, $fingerprint, $passphrase);
    }

    public function encrypt($plaintext)
    {
        return gnupg_encrypt($this->handle, $plaintext);
    }

    public function decrypt($ciphertext)
    {
        return gnupg_decrypt($this->handle, $ciphertext);
    }

    /**
     * @param string $inputPath
     * @param string $outputPath
     * @return int|bool
     */
    public function encryptFile($inputPath, $outputPath)
    {
        $plaintext = file_get_contents($inputPath);

        if ($plaintext === false) {
            return false;
        }

        $ciphertext = $this->encrypt($plaintext);

        if ($ciphertext === false) {
            return false;
        }

        return file_put_contents($outputPath, $ciphertext);
    }

    /**
     * @param string $inputPath
     * @param string $outputPath
     * @return int|bool
     */
    public function decryptFile($inputPath, $outputPath)
    {
        $ciphertext = file_get_contents($inputPath);

        if ($ciphertext === false) {
            return false;
        }

        $plaintext = $this->decrypt($ciphertext);

        if ($plaintext === false) {
            return false;
        }

        return file_put_contents($outputPath, $plaintext);
    }

}