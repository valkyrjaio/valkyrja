<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * It is used to set the application service container instances.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Model Instances
    |--------------------------------------------------------------------------
    |
    | TODO: Fill with explanation
    |
    */
    /**
     * User Model
     *
     * @description
     *  Will return a new instance of the User Model each instance request.
     */
    \App\Models\User::class                   => [
        function () {
            $classInstance = env('models.user');

            return new $classInstance;
        },
    ],

    /**
     * Article Model
     *
     * @description
     *  Will return a new instance of the Article Model each instance request.
     */
    \App\Models\Article::class                => [
        function () {
            $classInstance = env('models.article');

            return new $classInstance;
        },
    ],

    /*
    |--------------------------------------------------------------------------
    | Controller Instances
    |--------------------------------------------------------------------------
    |
    | TODO: Fill with explanation
    |
    */
    /**
     * Home Controller
     *
     * @description
     *  Will return the same instance of the Home Controller each instance request.
     */
    \App\Controllers\HomeController::class    => function () {
        return new \App\Controllers\HomeController(
            container(\App\Models\User::class)
        );
    },

    /**
     * Article Controller
     *
     * @description
     *  Will return a the same instance of the Article Controller each instance request.
     */
    \App\Controllers\ArticleController::class => function () {
        return new \App\Controllers\ArticleController(
            container(\App\Models\Article::class)
        );
    },

    /*
    |--------------------------------------------------------------------------
    | Main Classes Instances
    |--------------------------------------------------------------------------
    |
    | TODO: Fill with explanation
    |
    */
];
