<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Exceptions;

use Throwable;
use Valkyrja\Application;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Response;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\Util\Misc;

/**
 * Class NativeExceptionHandler.
 *
 * @author Melech Mizrachi
 */
class NativeExceptionHandler implements ExceptionHandler
{
    /**
     * Whether debug is enabled or not.
     *
     * @var bool
     */
    public static bool $enabled = false;

    /**
     * @var Application
     */
    protected Application $app;

    /**
     * NativeExceptionHandler constructor.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * Enable debug mode.
     *
     * @param int  $errorReportingLevel [optional] The error reporting level
     * @param bool $displayErrors       [optional] Whether to display errors
     *
     * @return void
     */
    public static function enable(int $errorReportingLevel = E_ALL, bool $displayErrors = false): void
    {
        // If debug is already enabled
        if (static::$enabled) {
            // Don't do things twice
            return;
        }

        // Debug is enabled
        static::$enabled = true;

        $run = new Run();

        // We want the error page to be shown by default, if this is a
        // regular request, so that's the first thing to go into the stack:
        $run->pushHandler(new PrettyPageHandler());

        // Now, we want a second handler that will run before the error page,
        // and immediately return an error message in JSON format, if something
        // goes awry.
        if (Misc::isAjaxRequest()) {
            $jsonHandler = new JsonResponseHandler();

            // You can also tell JsonResponseHandler to give you a full stack trace:
            // $jsonHandler->addTraceToOutput(true);

            // You can also return a result compliant to the json:api spec
            // re: http://jsonapi.org/examples/#error-objects
            // tl;dr: error[] becomes errors[[]]
            $jsonHandler->setJsonApi(true);

            // And push it into the stack:
            $run->pushHandler($jsonHandler);
        }

        // That's it! Register Whoops and throw a dummy exception:
        $run->register();
    }

    /**
     * Get a response from a throwable.
     *
     * @param Throwable $exception
     *
     * @throws Throwable
     *
     * @return Response
     */
    public function response(Throwable $exception): Response
    {
        if ($exception instanceof HttpException) {
            if ($exception->getResponse() !== null) {
                return $exception->getResponse();
            }

            if ($this->app->debug()) {
                throw $exception;
            }

            try {
                $statusCode = $exception->getStatusCode();

                return $this->app->response($this->app->view("errors/$statusCode")->render(), $statusCode);
            } catch (Throwable $exception) {
                return $this->getDefaultResponse();
            }
        }

        if ($this->app->debug()) {
            throw $exception;
        }

        return $this->getDefaultResponse();
    }

    /**
     * Get the default 500 error view.
     *
     * @return Response
     */
    protected function getDefaultResponse(): Response
    {
        return $this->app->response($this->app->view('errors/500')->render(), StatusCode::INTERNAL_SERVER_ERROR);
    }
}
