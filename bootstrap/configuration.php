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
 * Application Environment Variables
 *---------------------------------------------------------------------
 *
 * Environment variables are a convenient way to have different
 * configurations for each environment you develop on, or run
 * your application on. Basic examples are local (dev), qa,
 * staging, and production.
 *
 */

// Require the environment variables to overwrite default config
$env = require_once __DIR__ . '/../.env.php';
// Set the environment variables to overwrite default config
$app->setEnvs($env);

/*
 *---------------------------------------------------------------------
 * Application Configuration Variables
 *---------------------------------------------------------------------
 *
 * Configuration variables are a great way to modify the application
 * and how it runs to your specific needs.
 *
 */

// Require the default config variables
$config = require_once __DIR__ . '/../config/config.php';
// Set the default config variables
$app->setConfigVars($config);
// Set the timezone for the application process
$app->setTimezone();

$configs = new config\Configs($app);

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
