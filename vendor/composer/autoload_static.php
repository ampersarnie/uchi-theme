<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit98e4a3e56cbd08ed6545ba8299ce3c6b
{
    public static $files = array (
        '714cfc61b67ee4d4cbc6006f1be91138' => __DIR__ . '/../..' . '/inc/asset-settings.php',
        '2543aaba2539997d881c15680b97d7a1' => __DIR__ . '/../..' . '/inc/setup.php',
    );

    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
        'A' => 
        array (
            'Ampersarnie\\WP\\Uchi\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
        'Ampersarnie\\WP\\Uchi\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static $classMap = array (
        'Ampersarnie\\WP\\Uchi\\BlockQueries' => __DIR__ . '/../..' . '/inc/class-block-queries.php',
        'Ampersarnie\\WP\\Uchi\\Clearance' => __DIR__ . '/../..' . '/inc/class-clearance.php',
        'Ampersarnie\\WP\\Uchi\\Loader' => __DIR__ . '/../..' . '/inc/class-loader.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit98e4a3e56cbd08ed6545ba8299ce3c6b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit98e4a3e56cbd08ed6545ba8299ce3c6b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit98e4a3e56cbd08ed6545ba8299ce3c6b::$classMap;

        }, null, ClassLoader::class);
    }
}
