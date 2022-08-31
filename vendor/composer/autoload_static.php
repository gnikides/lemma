<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6127f957e06e74d30c82577adcabb77f
{
    public static $files = array (
        '9e4824c5afbdc1482b6025ce3d4dfde8' => __DIR__ . '/..' . '/league/csv/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lemma\\' => 6,
            'League\\Csv\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lemma\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'League\\Csv\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/csv/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6127f957e06e74d30c82577adcabb77f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6127f957e06e74d30c82577adcabb77f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6127f957e06e74d30c82577adcabb77f::$classMap;

        }, null, ClassLoader::class);
    }
}