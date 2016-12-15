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
    App\Controllers\HomeController::class,
    function () use ($app) {
        return new App\Controllers\HomeController($app);
    }
);
