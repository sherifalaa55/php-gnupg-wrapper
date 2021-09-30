<?php

include '../vendor/autoload.php';

use Sherifai\Pgp\GnupgWrapper;
use Sherifai\Pgp\KeystoreManager;

$keypairDir = "/tmp";

// $msg = "-----BEGIN PGP MESSAGE----- hQGMAy5IHMeVD+zFAQv8CNHWV7TKOvKWSQUKTtuDnFKkCWq6bkVO/4k4Dtgbxomy XNsa7KOiW0ai8aedX9hrZWDjKtPKlaufAH4b6X3JbWbURilCo0JmhsXF7X55Rn2Y KZJ1nuteQvDmtEqhneZSGTCWHzGcGhOx63gtaBY7ajKDeP6L1ru3bzndico5wRBg l5CKLgL3b0pkKvavlXJAKqT7AD+Fklo0SVb7dkhfJlHiu84ut2bUJneMxS3iGguV mGN+DFx4/J96r0rMtGmuD4rUsdscwHrjuBoCD0tXMbSjPVJxXFgbAgyl/gYl6ENZ WcB/1Be4TI+TBcfUfm1HxT/3ZkVcx+eWDdOL4yq3wyM4gphV6q4z5i6Mk8mtJLs/ IEJGU0KkIEShEcUROZWUIrv30UzGaSZ9isDdXYgN/8/yIZTJlBNQiec0rwpBi/sm 1zRvpCsknLUt1zXJxjIig8qb0bJyT+xUywuyL1D8HGj7pb5uF4jqdsYRO+sHxZZl Ylb5jTPVQN87WmnpKXSk0kUB2yBPJkHSKz8e7kO14TEYr76M/JS9HFsAxXltFhL8 FKfYpJkBPwl62SBY+U2d6Bj2RxUS+BA38XqHQcrRwgeba8bptWU= =y6de -----END PGP MESSAGE-----";
$msg = "-----BEGIN PGP MESSAGE-----

hQGMAy5IHMeVD+zFAQwAqUs1OK/sb/G/zvLn0UfOmwFfca5DT06JkGI4SyyvguF2
b65dvy3WeZSItBC7X50G+YyKgXC/eaKj1cPjBCaguGzzXULegrosU9+U3d04zqcm
BLaZTqZdlDrGP5c6QYT6MQuY2tbedyiPVc7pbXdh1393h3+/0SJSay2cAbayE7Gj
5762k3ddLa7LlWGzubQO4WZwRirmxxtxz/b+I1cnkn7HAo4YLCIrFiRL5h4teXjz
ofjwMO10k2hGbA/idJ1bLq1D+LWW2SfFCHqfhXJSEhV96fKY1IiTR96rU5bX2ccw
RJP4Bj9SEwmbOTpeOqTCkL1uAp4kESSj6EGLHagpeBTRuR+qB3enqtBZlfv6mOO9
QmaOakHr1HOQcYFil0J/9NcCs8TRw3eHTcNyToSsCWydG+JUQ4ztdQmOUswXmZut
CGWikGlTQYznQ5dq0D2PF2HbFqQV8LyBJ/TgObkcVv6y8d81NUJwXxnzom3P0dBr
nusy0F0S4N6GNyksApCW0kUBJVOztlDp/Yh5R9BFsG+JNLdiaF8hvl9AtmmfDBwa
HjSXv4m1ZhprfEpSyjv7E8V5mhEhV9sSbrA0b28oL6eRhVcJCh8=
=PE17
-----END PGP MESSAGE-----";

$keystore = new KeystoreManager($keypairDir);

$keys = $keystore->listKeys();
$kp = $keystore->findKeyPair($_GET['username']);

// echo '<pre>' , var_dump($keys) , '</pre>', PHP_EOL;
// echo '<pre>' , var_dump($kp) , '</pre>', PHP_EOL;
// die();


$wrapper = new GnupgWrapper($keypairDir);
// $wrapper->addEncryptKey($kp->fingerPrint);
// $wrapper->addDecryptKey($kp->fingerPrint);

// $msg = $wrapper->encrypt('this is test message');
// $msg = $wrapper->encrypt('this is test message');
// echo '<pre>' , var_dump($m   sg) , '</pre>', PHP_EOL;
// $ciphertext = "fdsaf";
// echo $ciphertext, PHP_EOL;
$decrypted = $wrapper->decrypt($msg);
echo '<pre>' , var_dump($decrypted) , '</pre>', PHP_EOL;
// echo $wrapper->decrypt($msg), PHP_EOL;