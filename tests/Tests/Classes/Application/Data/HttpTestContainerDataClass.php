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

namespace Valkyrja\Tests\Classes\Application\Data;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Dispatch\Provider\DispatchServiceProvider;
use Valkyrja\Event\Collection\Contract\ListenerCollectionContract;
use Valkyrja\Event\Collector\Contract\ListenerCollectorContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Event\Dispatcher\Contract\EventDispatcherContract;
use Valkyrja\Event\Provider\EventServiceProvider;
use Valkyrja\Http\Message\Provider\HttpMessageServiceProvider;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Middleware\Provider\HttpMiddlewareServiceProvider;
use Valkyrja\Http\Routing\Cli\Command\ListCommand;
use Valkyrja\Http\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Http\Routing\Collector\Contract\RouteCollectorContract;
use Valkyrja\Http\Routing\Data\HttpRoutingData;
use Valkyrja\Http\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Http\Routing\Factory\Contract\ResponseFactoryContract as RoutingResponseFactoryContract;
use Valkyrja\Http\Routing\Matcher\Contract\MatcherContract;
use Valkyrja\Http\Routing\Processor\Contract\ProcessorContract;
use Valkyrja\Http\Routing\Provider\HttpRoutingCliServiceProvider;
use Valkyrja\Http\Routing\Provider\HttpRoutingServiceProvider;
use Valkyrja\Http\Routing\Url\Contract\UrlContract;
use Valkyrja\Http\Server\Handler\Contract\RequestHandlerContract;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\RequestStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteMatched\ResponseStructMiddleware;
use Valkyrja\Http\Server\Middleware\RouteNotMatched\ViewRouteNotMatchedMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\LogThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Middleware\ThrowableCaught\ViewThrowableCaughtMiddleware;
use Valkyrja\Http\Server\Provider\HttpServerServiceProvider;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Log\Logger\NullLogger;
use Valkyrja\Log\Logger\PsrLogger;
use Valkyrja\Log\Provider\LogServiceProvider;
use Valkyrja\Tests\Classes\Application\Provider\HttpContainerDataProviderClass;
use Valkyrja\Tests\Classes\Application\Provider\HttpRoutingDataProviderClass;
use Valkyrja\View\Factory\Contract\ResponseFactoryContract as ViewResponseFactoryContract;
use Valkyrja\View\Provider\ViewServiceProvider;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Renderer\OrkaRenderer;
use Valkyrja\View\Renderer\PhpRenderer;
use Valkyrja\View\Renderer\TwigRenderer;

final readonly class HttpTestContainerDataClass extends ContainerData
{
    public function __construct()
    {
        parent::__construct(
            deferredCallback: [
                ContainerData::class                  => [HttpContainerDataProviderClass::class, 'publishData'],
                DispatcherContract::class             => [DispatchServiceProvider::class, 'publishDispatcher'],
                ListenerCollectorContract::class      => [EventServiceProvider::class, 'publishAttributesListenerCollector'],
                EventDispatcherContract::class        => [EventServiceProvider::class, 'publishDispatcher'],
                ListenerCollectionContract::class     => [EventServiceProvider::class, 'publishListenerCollection'],
                EventData::class                      => [EventServiceProvider::class, 'publishData'],
                ResponseFactoryContract::class        => [HttpMessageServiceProvider::class, 'publishResponseFactory'],
                RequestReceivedHandlerContract::class => [HttpMiddlewareServiceProvider::class, 'publishRequestReceivedHandler'],
                ThrowableCaughtHandlerContract::class => [HttpMiddlewareServiceProvider::class, 'publishThrowableCaughtHandler'],
                RouteMatchedHandlerContract::class    => [HttpMiddlewareServiceProvider::class, 'publishRouteMatchedHandler'],
                RouteNotMatchedHandlerContract::class => [HttpMiddlewareServiceProvider::class, 'publishRouteNotMatchedHandler'],
                RouteDispatchedHandlerContract::class => [HttpMiddlewareServiceProvider::class, 'publishRouteDispatchedHandler'],
                SendingResponseHandlerContract::class => [HttpMiddlewareServiceProvider::class, 'publishSendingResponseHandler'],
                TerminatedHandlerContract::class      => [HttpMiddlewareServiceProvider::class, 'publishTerminatedHandler'],
                RouterContract::class                 => [HttpRoutingServiceProvider::class, 'publishRouter'],
                RouteCollectionContract::class        => [HttpRoutingServiceProvider::class, 'publishRouteCollection'],
                MatcherContract::class                => [HttpRoutingServiceProvider::class, 'publishMatcher'],
                UrlContract::class                    => [HttpRoutingServiceProvider::class, 'publishUrl'],
                RouteCollectorContract::class         => [HttpRoutingServiceProvider::class, 'publishAttributesRouteCollector'],
                ProcessorContract::class              => [HttpRoutingServiceProvider::class, 'publishProcessor'],
                RoutingResponseFactoryContract::class => [HttpRoutingServiceProvider::class, 'publishResponseFactory'],
                HttpRoutingData::class                => [HttpRoutingDataProviderClass::class, 'publishData'],
                ListCommand::class                    => [HttpRoutingCliServiceProvider::class, 'publishListCommand'],
                RequestHandlerContract::class         => [HttpServerServiceProvider::class, 'publishRequestHandler'],
                LogThrowableCaughtMiddleware::class   => [HttpServerServiceProvider::class, 'publishLogThrowableCaughtMiddleware'],
                ViewThrowableCaughtMiddleware::class  => [HttpServerServiceProvider::class, 'publishViewThrowableCaughtMiddleware'],
                RequestStructMiddleware::class        => [HttpServerServiceProvider::class, 'publishRequestStructMiddleware'],
                ResponseStructMiddleware::class       => [HttpServerServiceProvider::class, 'publishResponseStructMiddleware'],
                ViewRouteNotMatchedMiddleware::class  => [HttpServerServiceProvider::class, 'publishViewRouteNotMatchedMiddleware'],
                CacheResponseMiddleware::class        => [HttpServerServiceProvider::class, 'publishCacheResponseMiddleware'],
                LoggerContract::class                 => [LogServiceProvider::class, 'publishLogger'],
                PsrLogger::class                      => [LogServiceProvider::class, 'publishPsrLogger'],
                NullLogger::class                     => [LogServiceProvider::class, 'publishNullLogger'],
                LoggerInterface::class                => [LogServiceProvider::class, 'publishLoggerInterface'],
                Logger::class                         => [LogServiceProvider::class, 'publishMonolog'],
                RendererContract::class               => [ViewServiceProvider::class, 'publishRenderer'],
                PhpRenderer::class                    => [ViewServiceProvider::class, 'publishPhpRenderer'],
                OrkaRenderer::class                   => [ViewServiceProvider::class, 'publishOrkaRenderer'],
                TwigRenderer::class                   => [ViewServiceProvider::class, 'publishTwigRenderer'],
                Environment::class                    => [ViewServiceProvider::class, 'publishTwigEnvironment'],
                ViewResponseFactoryContract::class    => [ViewServiceProvider::class, 'publishResponseFactory'],
            ],
        );
    }
}
