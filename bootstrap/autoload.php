<?php

/**
 * Application autoloader.
 *
 * @param string $prefix      The prefix to register
 * @param string $baseDir     The base directory to look under
 * @param string $deliminator [optional] The deliminator to replace to a directory separator
 *
 * @return void
 */
function autoloader($prefix, $baseDir, $deliminator = '\\')
{
    // Register a new autoload closure
    spl_autoload_register(
        function ($class) use ($prefix, $baseDir, $deliminator) {
            // does the class use the namespace prefix?
            $len = strlen($prefix);

            if (strncmp($prefix, $class, $len) !== 0) {
                // no, move to the next registered autoloader
                return;
            }

            // get the relative class name
            $relative_class = substr($class, $len);

            // replace the namespace prefix with the base directory, replace namespace
            // separators with directory separators in the relative class name, append
            // with .php
            $file = $baseDir . '/' . str_replace(
                    $deliminator,
                    '/',
                    $relative_class
                ) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        }
    );
}

$vendorDir = $baseDir . '/vendor/';

// Setup the auto loader for the Valkyrja namespace
// - Using our own auto loading for better optimization
autoloader('Valkyrja\\', $baseDir . '/' . 'framework');

// Setup the auto loader for the Application namespace
// - Using our own auto loading for better optimization
autoloader('App\\', $baseDir . '/' . 'app');
autoloader('GuzzleHttp\\', $vendorDir . 'guzzlehttp/guzzle/src');
autoloader('GuzzleHttp\\Promise\\', $vendorDir . 'guzzlehttp/promises/src');
autoloader('GuzzleHttp\\Psr7\\', $vendorDir . 'guzzlehttp/psr7/src');
autoloader('League\\Flysystem\\', $vendorDir . 'league/flysystem/src');
autoloader('Monolog\\', $vendorDir . 'monolog/monolog/src/Monolog');
autoloader('Predis\\', $vendorDir . 'predis/predis/src');
autoloader('Psr\\Log\\', $vendorDir . 'psr/log/Psr/Log');
autoloader('Psr\\Http\\Message\\', $vendorDir . 'psr/http-message/src');
autoloader('Twig_', $vendorDir . 'twig/twig/lib/Twig', '_');
