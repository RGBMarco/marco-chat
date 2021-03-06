<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0b9de2ec024d3c28a1d18fba52207cd8
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Mustache' => 
            array (
                0 => __DIR__ . '/..' . '/mustache/mustache/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0b9de2ec024d3c28a1d18fba52207cd8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0b9de2ec024d3c28a1d18fba52207cd8::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit0b9de2ec024d3c28a1d18fba52207cd8::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
