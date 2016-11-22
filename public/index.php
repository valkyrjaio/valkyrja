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
 * Application Auto Loader Assemble!
 *-------------------------------------------------------------------------
 *
 * Autoload all the application namespaces, dependencies, and files using
 * composer to both manage all dependencies as well as register everything
 * for us to use within the application.
 *
 */

require_once '../vendor/autoload.php';

/*
 *-------------------------------------------------------------------------
 * Setup The Application
 *-------------------------------------------------------------------------
 *
 * Let's setup the application by bootstrapping it. This will instanciate
 * the main application as well as add any required classes to the
 * service container, add environment variables, add config
 * variables, and add all the application routes.
 *
 */

$app = require_once '../bootstrap/app.php';

/*
 *-------------------------------------------------------------------------
 * Run The Application
 *-------------------------------------------------------------------------
 *
 * Now that the application has been bootstrapped and setup correctly
 * with all our requirements lets run it!
 *
 */

$app->run();
