<?php

include '../vendor/autoload.php';

use Sherifai\Pgp\GnupgWrapper;
use Sherifai\Pgp\KeystoreManager;

$keypairDir = getcwd() . '/keypair_passphrase_1599839034';

$keystore = new KeystoreManager($keypairDir);

$keys = $keystore->listKeys();

foreach ($keys[0]['subkeys'] as $subkey) {
    if ($subkey['can_encrypt'] === 1) {
        break;
    }
}

$wrapper = new GnupgWrapper($keypairDir);
$wrapper->addEncryptKey($subkey['fingerprint']);
$wrapper->addDecryptKey($subkey['fingerprint'], 'testpass');

$ciphertext = $wrapper->encrypt('this is test message');

echo $ciphertext, PHP_EOL;

echo $wrapper->decrypt($ciphertext), PHP_EOL;
