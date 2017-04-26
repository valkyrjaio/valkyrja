<?php

/*
 *-------------------------------------------------------------------------
 * Bind Application Container Instances
 *-------------------------------------------------------------------------
 *
 * TODO: ADD EXPLANATION
 *
 */

$app->container()->bind(
    (new \Valkyrja\Container\Service())
    ->setId(App\Controllers\HomeController::class)
    ->setClass(App\Controllers\HomeController::class)
    ->setDependencies([Valkyrja\Contracts\Application::class])
);
