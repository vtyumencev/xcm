<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitd29cf36a4def489646609b0ed6b57ae9
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

        spl_autoload_register(array('ComposerAutoloaderInitd29cf36a4def489646609b0ed6b57ae9', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitd29cf36a4def489646609b0ed6b57ae9', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitd29cf36a4def489646609b0ed6b57ae9::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
