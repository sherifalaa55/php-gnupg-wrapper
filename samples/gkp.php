<?php

include '../vendor/autoload.php';

use Sherifai\Pgp\KeystoreManager;

$keypairDir = getcwd() . '/keypair_' . time();

mkdir($keypairDir);

$manager = new KeystoreManager("/tmp");

try {

    $result = $manager->generateKeyPair(
        'John Doen',
        'john.doe@example.com',
        null,
        strtotime('+5 days')
    );

    echo var_dump($result);

} catch (\Exception $exception) {
    echo 'generate key error:', $exception->getMessage();
}



