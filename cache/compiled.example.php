<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * This is an example mock up of the compiled optimized file used for production.
 */

// Inline classes for framework here.

// Inline helpers.php here.

$env = [];
$routes = [];
$container = [];

$app->setEnvs($env);
$app->setTimezone();
$app->router()
    ->setRoutes($routes);
$app->setServiceContainer($container);

// Inline classes for application here (from config/compile.php).
