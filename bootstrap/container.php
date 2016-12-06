<?php

/*
 *-------------------------------------------------------------------------
 * Bind Application Container Instances
 *-------------------------------------------------------------------------
 *
 * TODO: ADD EXPLANATION
 *
 */

$app->container()->instance(
    App\Controllers\HomeController::class,
    function () use ($app) {
        return new App\Controllers\HomeController($app);
    }
);
