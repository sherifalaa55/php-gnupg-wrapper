<?php

include '../vendor/autoload.php';

use Sherifai\Pgp\GnupgWrapper;
use Sherifai\Pgp\KeystoreManager;

$keypairDir = "/tmp";

$keystore = new KeystoreManager($keypairDir);

$keys = $keystore->listKeys();
$kp = $keystore->findKeyPair('John Doen');

echo '<pre>' , var_dump($keys) , '</pre>', PHP_EOL;
echo '<pre>' , var_dump($kp) , '</pre>', PHP_EOL;
die();
foreach ($keys[0]['subkeys'] as $subkey) {
    if ($subkey['can_encrypt'] === 1) {
        break;
    }
}

$wrapper = new GnupgWrapper($keypairDir);
$wrapper->addEncryptKey($subkey['fingerprint']);
$wrapper->addDecryptKey($subkey['fingerprint']);

$ciphertext = $wrapper->encrypt('this is test message');
// $ciphertext = "fdsaf";
echo $ciphertext, PHP_EOL;

echo $wrapper->decrypt($ciphertext), PHP_EOL;