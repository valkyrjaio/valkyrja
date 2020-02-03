<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Valkyrja\Annotation\Annotations;
use Valkyrja\Application\Application;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Client\Client;
use Valkyrja\Console\Console;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Output\Output;
use Valkyrja\Container\Container;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Event\Events;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\Response;
use Valkyrja\Logger\Logger;
use Valkyrja\Mail\Mail;
use Valkyrja\ORM\EntityManager;
use Valkyrja\Session\Session;
use Valkyrja\View\View;

if (! function_exists('app')) {
    /**
     * Return the global $app variable.
     *
     * @return Application
     */
    function app(): Application
    {
        return Valkyrja::app();
    }
}

if (! function_exists('abort')) {
    /**
     * Abort the application due to error.
     *
     * @param int      $statusCode The status code to use
     * @param string   $message    [optional] The Exception message to throw
     * @param array    $headers    [optional] The headers to send
     * @param int      $code       [optional] The Exception code
     * @param Response $response   [optional] The Response to send
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return void
     */
    function abort(
        int $statusCode = StatusCode::NOT_FOUND,
        string $message = '',
        array $headers = [],
        int $code = 0,
        Response $response = null
    ): void {
        app()->abort($statusCode, $message, $headers, $code, $response);
    }
}

if (! function_exists('abortResponse')) {
    /**
     * Abort the application due to error with a given response to send.
     *
     * @param Response $response The Response to send
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return void
     */
    function abortResponse(Response $response): void
    {
        app()->abort(0, '', [], 0, $response);
    }
}

if (! function_exists('annotations')) {
    /**
     * Return the annotations instance from the container.
     *
     * @return Annotations
     */
    function annotations(): Annotations
    {
        return app()->annotations();
    }
}

if (! function_exists('client')) {
    /**
     * Return the client instance from the container.
     *
     * @return Client
     */
    function client(): Client
    {
        return app()->client();
    }
}

if (! function_exists('config')) {
    /**
     * Get the config.
     *
     * @param string $key     [optional] The key to get
     * @param mixed  $default [optional] The default value if the key is not found
     *
     * @return mixed
     */
    function config(string $key = null, $default = null)
    {
        return app()->config($key, $default);
    }
}

if (! function_exists('console')) {
    /**
     * Get console.
     *
     * @return Console
     */
    function console(): Console
    {
        return app()->console();
    }
}

if (! function_exists('container')) {
    /**
     * Get container.
     *
     * @return Container
     */
    function container(): Container
    {
        return app()->container();
    }
}

if (! function_exists('env')) {
    /**
     * Get an environment variable.
     *
     * @param string $key     [optional] The variable to get
     * @param mixed  $default [optional] The default value to return
     *
     * @return mixed
     */
    function env(string $key = null, $default = null)
    {
        // Does not use the app() helper due to the self::$instance property
        // that Valkyrja::app() relies on has not been set yet when
        // this helper may be used.
        return Valkyrja::env($key, $default);
    }
}

if (! function_exists('events')) {
    /**
     * Get events.
     *
     * @return Events
     */
    function events(): Events
    {
        return app()->events();
    }
}

if (! function_exists('filesystem')) {
    /**
     * Get filesystem.
     *
     * @return Filesystem
     */
    function filesystem(): Filesystem
    {
        return app()->filesystem();
    }
}

if (! function_exists('input')) {
    /**
     * Get input.
     *
     * @return Input
     */
    function input(): Input
    {
        return container()->get(Contract::INPUT);
    }
}

if (! function_exists('kernel')) {
    /**
     * Get kernel.
     *
     * @return Kernel
     */
    function kernel(): Kernel
    {
        return app()->kernel();
    }
}

if (! function_exists('consoleKernel')) {
    /**
     * Get console kernel.
     *
     * @return \Valkyrja\Console\Kernel
     */
    function consoleKernel(): \Valkyrja\Console\Kernel
    {
        return app()->consoleKernel();
    }
}

if (! function_exists('vcrypt')) {
    /**
     * Get the crypt.
     *
     * @return Crypt
     */
    function vcrypt(): Crypt
    {
        return app()->crypt();
    }
}

if (! function_exists('entityManager')) {
    /**
     * Get the entity manager.
     *
     * @return EntityManager
     */
    function entityManager(): EntityManager
    {
        return app()->entityManager();
    }
}

if (! function_exists('logger')) {
    /**
     * Get logger.
     *
     * @return Logger
     */
    function logger(): Logger
    {
        return app()->logger();
    }
}

if (! function_exists('mail')) {
    /**
     * Get mail.
     *
     * @return Mail
     */
    function mail(): Mail
    {
        return app()->mail();
    }
}

if (! function_exists('output')) {
    /**
     * Get output.
     *
     * @return Output
     */
    function output(): Output
    {
        return container()->get(Contract::OUTPUT);
    }
}

if (! function_exists('session')) {
    /**
     * Return the session.
     *
     * @return Session
     */
    function session(): Session
    {
        return app()->session();
    }
}

if (! function_exists('view')) {
    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return View
     */
    function view(string $template = '', array $variables = []): View
    {
        return app()->view($template, $variables);
    }
}

if (! function_exists('dd')) {
    /**
     * Dump the passed variables and die.
     *
     * @param mixed
     *  The arguments to dump
     *
     * @return void
     */
    function dd(): void
    {
        var_dump(func_get_args());

        die(1);
    }
}
