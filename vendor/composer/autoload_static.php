<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaacae2c79dd7e3a2a8b9c4f8bdd2cf1b
{
    public static $files = array (
        '066b93df3c9c45c8528179c0a5d66819' => __DIR__ . '/..' . '/kokororin/pixiv-api-php/PixivBase.php',
        '5122f97648f922a0fff5637a67822d94' => __DIR__ . '/..' . '/kokororin/pixiv-api-php/PixivAPI.php',
        '2ad745bc5684ddf1b5f9965f3125d844' => __DIR__ . '/..' . '/kokororin/pixiv-api-php/PixivAppAPI.php',
    );

    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Curl\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Curl\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-curl-class/php-curl-class/src/Curl',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitaacae2c79dd7e3a2a8b9c4f8bdd2cf1b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitaacae2c79dd7e3a2a8b9c4f8bdd2cf1b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}