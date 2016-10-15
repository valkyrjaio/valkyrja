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
 * Setup The Application With Compiled : A Need For Speed
 *-------------------------------------------------------------------------
 *
 * TODO: Fill with explanation
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
 * Setup The Application Environment Variables
 *---------------------------------------------------------------------
 *
 * TODO: Fill with explanation
 *
 */

// Require the environment variables to overwrite default config
$env = require_once __DIR__ . '/../.env.php';
// Set the environment variables to overwrite default config
$app->setEnvs($env);
// Set the timezone for the application process
$app->setTimezone();

/*
 *---------------------------------------------------------------------
 * Setup The Application Default Configuration
 *---------------------------------------------------------------------
 *
 * TODO: Fill with explanation
 *
 */

// Require the default config variables
$config = require_once __DIR__ . '/../config/config.php';
// Set the default config variables
$app->setConfigVars($config);

/*
 *---------------------------------------------------------------------
 * Setup The Application Service Container : Dependency Injection
 *---------------------------------------------------------------------
 *
 * TODO: Fill with explanation
 *
 */

// Require any dependency injectable definitions
$container = require_once __DIR__ . '/../config/container.php';
// Set the container variables
$app->setServiceContainer($container);

/*
 *---------------------------------------------------------------------
 * Setup The Application Routes
 *---------------------------------------------------------------------
 *
 * TODO: Fill with explanation
 *
 */

// Require the routes
$routes = require_once __DIR__ . '/../routes/routes.php';
