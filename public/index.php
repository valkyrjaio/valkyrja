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
 * TODO: Fill with explanation
 *
 */

$baseDir = realpath(__DIR__ . '/../');

// require_once '../vendor/autoload.php';
require_once '../bootstrap/autoload.php';

/*
 *-------------------------------------------------------------------------
 * Setup The Application
 *-------------------------------------------------------------------------
 *
 * TODO: Fill with explanation
 *
 */

$app = require_once '../bootstrap/app.php';

/*
 *-------------------------------------------------------------------------
 * Run The Application
 *-------------------------------------------------------------------------
 *
 * TODO: Fill with explanation
 *
 */

$app->run();
