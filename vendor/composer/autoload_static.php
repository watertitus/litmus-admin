<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaa0b42cefb2ea70b93dee237c891fa1e
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitaa0b42cefb2ea70b93dee237c891fa1e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitaa0b42cefb2ea70b93dee237c891fa1e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitaa0b42cefb2ea70b93dee237c891fa1e::$classMap;

        }, null, ClassLoader::class);
    }
}
