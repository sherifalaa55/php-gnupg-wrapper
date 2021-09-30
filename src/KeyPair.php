<?php

namespace Sherifai\Pgp;

class KeyPair
{
    public $publicKey;
    public $privateKey;
    public $fingerPrint;

    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    public function setFingerPrint($fingerPrint)
    {
        $this->fingerPrint = $fingerPrint;
    }
}
