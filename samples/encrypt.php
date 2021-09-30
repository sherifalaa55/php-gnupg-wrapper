<?php

include '../vendor/autoload.php';

use Sherifai\Pgp\GnupgWrapper;
use Sherifai\Pgp\KeystoreManager;

$keypairDir = "/tmp";

$keystore = new KeystoreManager($keypairDir);

$keys = $keystore->listKeys();
$kp = $keystore->findKeyPair($_GET['username']);

// echo '<pre>' , var_dump($keys) , '</pre>', PHP_EOL;
// echo '<pre>' , var_dump($kp) , '</pre>', PHP_EOL;
// echo '<pre>' , var_dump($_GET) , '</pre>', PHP_EOL;
// die();
foreach ($keys[0]['subkeys'] as $subkey) {
    if ($subkey['can_encrypt'] === 1) {
        break;
    }
}

$wrapper = new GnupgWrapper($keypairDir);
$wrapper->addEncryptKey($kp->fingerPrint);
$wrapper->addDecryptKey($kp->fingerPrint);

$ciphertext = $wrapper->encrypt($_GET['msg']);
// $ciphertext = "fdsaf";
echo '<pre>' , var_dump($ciphertext) , '</pre>', PHP_EOL;

// echo $wrapper->decrypt($ciphertext), PHP_EOL;