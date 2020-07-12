<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http\Providers;

use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Http\EmptyResponse;
use Valkyrja\Http\Factories\RequestFactory;
use Valkyrja\Http\HtmlResponse;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Http\TextResponse;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProvider extends Provider
{
    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [
            ResponseFactory::class  => 'publishResponseFactory',
            Request::class          => 'publishRequest',
            Response::class         => 'publishResponse',
            EmptyResponse::class    => 'publishEmptyResponse',
            HtmlResponse::class     => 'publishHtmlResponse',
            JsonResponse::class     => 'publishJsonResponse',
            RedirectResponse::class => 'publishRedirectResponse',
            TextResponse::class     => 'publishTextResponse',
        ];
    }

    /**
     * The items provided by this provider.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [
            ResponseFactory::class,
            Request::class,
            Response::class,
            EmptyResponse::class,
            HtmlResponse::class,
            JsonResponse::class,
            RedirectResponse::class,
            TextResponse::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
    }

    /**
     * Publish the response factory service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishResponseFactory(Container $container): void
    {
        $container->setSingleton(
            ResponseFactory::class,
            new \Valkyrja\Http\Factories\ResponseFactory(
                $container
            )
        );
    }

    /**
     * Publish the request service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRequest(Container $container): void
    {
        $container->setSingleton(
            Request::class,
            RequestFactory::fromGlobals()
        );
    }

    /**
     * Publish the response service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishResponse(Container $container): void
    {
        $container->setSingleton(
            Response::class,
            new \Valkyrja\Http\Responses\Response()
        );
    }

    /**
     * Publish the empty response service.
     *
     * @param Container $container
     *
     * @return void
     */
    public static function publishEmptyResponse(Container $container): void
    {
        $container->setSingleton(
            EmptyResponse::class,
            new \Valkyrja\Http\Responses\EmptyResponse()
        );
    }

    /**
     * Publish the html response service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishHtmlResponse(Container $container): void
    {
        $container->setSingleton(
            HtmlResponse::class,
            new \Valkyrja\Http\Responses\HtmlResponse()
        );
    }

    /**
     * Publish the json response service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishJsonResponse(Container $container): void
    {
        $container->setSingleton(
            JsonResponse::class,
            new \Valkyrja\Http\Responses\JsonResponse()
        );
    }

    /**
     * Publish the redirect response service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishRedirectResponse(Container $container): void
    {
        $container->setSingleton(
            RedirectResponse::class,
            new \Valkyrja\Http\Responses\RedirectResponse()
        );
    }

    /**
     * Publish the text response service.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publishTextResponse(Container $container): void
    {
        $container->setSingleton(
            TextResponse::class,
            new \Valkyrja\Http\Responses\TextResponse()
        );
    }
}
