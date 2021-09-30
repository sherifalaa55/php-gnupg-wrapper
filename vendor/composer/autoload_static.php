<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7112488a35b66a58dd698756e5ade390
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Sherifai\\Pgp\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Sherifai\\Pgp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7112488a35b66a58dd698756e5ade390::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7112488a35b66a58dd698756e5ade390::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7112488a35b66a58dd698756e5ade390::$classMap;

        }, null, ClassLoader::class);
    }
}
