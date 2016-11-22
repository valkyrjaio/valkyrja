<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 *-------------------------------------------------------------------------
 * Configure The Application With Compiled : A Need For Speed
 *-------------------------------------------------------------------------
 *
 * If the application is in production its advised to optimize it with
 * the CLI optimize command. This will flatten all the configuration
 * files, framework classes, and application classes into a single
 * file for faster filesystem readability. In addition it will
 * Set routes to an array for faster loading.
 *
 */

// If there is a compiled version of the application use it!
if (file_exists(__DIR__ . '/../cache/compiled.php')) {
    // Require the compiled file
    require_once __DIR__ . '/../cache/compiled.php';

    // Set the application as using compiled
    $app->setCompiled();

    return;
}

/*
 *---------------------------------------------------------------------
 * Application Configuration Variables
 *---------------------------------------------------------------------
 *
 * Configuration variables are a great way to modify the application
 * and how it runs to your specific needs.
 *
 */

if (class_exists(config\Config::class)) {
    $config = new config\Config($app);
}
else {
    $config = new Valkyrja\Config\Config($app);
}


// Set the timezone for the application process
$app->setTimezone();

/*
 *---------------------------------------------------------------------
 * Application Service Container : Dependency Injection
 *---------------------------------------------------------------------
 *
 * Adding more instances to the service container is a great way to
 * ensure your application is setup for ease of change in the
 * future.
 *
 */

// Require any dependency injectable definitions
$serviceContainer = require_once __DIR__ . '/../config/container.php';
// Set the container variables
$container->setServiceContainer($serviceContainer);
