<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit11d25ff151a9f2ada44db42ecdcc9b37
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Porabote\\' => 9,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Porabote\\' => 
        array (
            0 => __DIR__ . '/../..' . '/porabote/src',
        ),
        'App\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit11d25ff151a9f2ada44db42ecdcc9b37::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit11d25ff151a9f2ada44db42ecdcc9b37::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit11d25ff151a9f2ada44db42ecdcc9b37::$classMap;

        }, null, ClassLoader::class);
    }
}
