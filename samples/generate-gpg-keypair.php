<?php

include '../vendor/autoload.php';

use Tugrul\Pgp\KeystoreManager;

$keypairDir = getcwd() . '/keypair_' . time();

mkdir($keypairDir);

$manager = new KeystoreManager($keypairDir);

try {

    $result = $manager->generateKey(
        'John Doe',
        'john.doe@example.com',
        null,
        strtotime('+5 days')
    );

    if ($result === 0) {
        echo '----- key generation success -----', PHP_EOL;
        echo $manager->getCommandResult(), PHP_EOL;
        echo $manager->getConfiguration(), PHP_EOL;

        // print_r($manager->listKeys());
    } else {
        echo '----- key generation fail -----', PHP_EOL;
        echo $manager->getCommandError(), PHP_EOL;
    }

} catch (\Exception $exception) {
    echo 'generate key error:', $exception->getMessage();
}



