<?php

include '../vendor/autoload.php';

use Sherifai\Pgp\GnupgWrapper;
use Sherifai\Pgp\KeystoreManager;

$keypairDir = getcwd() . '/keypair_1599838940';

$keystore = new KeystoreManager($keypairDir);

$keys = $keystore->listKeys();

foreach ($keys[0]['subkeys'] as $subkey) {
    if ($subkey['can_encrypt'] === 1) {
        break;
    }
}

$wrapper = new GnupgWrapper($keypairDir);
$wrapper->addEncryptKey($subkey['fingerprint']);
$wrapper->addDecryptKey($subkey['fingerprint']);

$ciphertext = $wrapper->encrypt('this is test message');

echo $ciphertext, PHP_EOL;

echo $wrapper->decrypt($ciphertext), PHP_EOL;