<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitc37ac5c0ce49147182dac2e834aec239
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitc37ac5c0ce49147182dac2e834aec239', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitc37ac5c0ce49147182dac2e834aec239', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitc37ac5c0ce49147182dac2e834aec239::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
