<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit58088ce2a3de9c2c3a3942de5457d027
{
    public static $prefixLengthsPsr4 = array (
        'e' => 
        array (
            'elrenardo\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'elrenardo\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'AltoRouter' => __DIR__ . '/..' . '/altorouter/altorouter/AltoRouter.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit58088ce2a3de9c2c3a3942de5457d027::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit58088ce2a3de9c2c3a3942de5457d027::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit58088ce2a3de9c2c3a3942de5457d027::$classMap;

        }, null, ClassLoader::class);
    }
}
