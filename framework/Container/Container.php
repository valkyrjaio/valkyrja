<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

use Psr\Log\LoggerInterface;

use Valkyrja\Annotations\Annotations;
use Valkyrja\Annotations\AnnotationsParser;
use Valkyrja\Container\Exceptions\InvalidContextException;
use Valkyrja\Container\Exceptions\InvalidServiceIdException;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Annotations\Annotations as AnnotationsContract;
use Valkyrja\Contracts\Annotations\AnnotationsParser as AnnotationsParserContract;
use Valkyrja\Contracts\Http\Client as ClientContract;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Contracts\Http\JsonResponse as JsonResponseContract;
use Valkyrja\Contracts\Http\RedirectResponse as RedirectResponseContract;
use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\ResponseBuilder as ResponseBuilderContract;
use Valkyrja\Contracts\Logger\Logger as LoggerContract;
use Valkyrja\Contracts\Routing\Annotations\RouteAnnotations as RouteAnnotationsContract;
use Valkyrja\Contracts\Routing\Router as RouterContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Http\Client;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Logger\Enums\LogLevel;
use Valkyrja\Logger\Logger;
use Valkyrja\Routing\Annotations\RouteAnnotations;
use Valkyrja\Routing\Router;
use Valkyrja\View\View;

/**
 * Class Container
 *
 * @package Valkyrja\Container
 *
 * @author  Melech Mizrachi
 */
class Container implements ContainerContract
{
    use Dispatcher;

    /**
     * The aliases.
     *
     * @var string[]
     */
    protected $aliases = [];

    /**
     * The services.
     *
     * @var \Valkyrja\Container\Service[]
     */
    protected $services = [];

    /**
     * The singletons.
     *
     * @var array
     */
    protected $singletons = [];

    /**
     * Set an alias to the container.
     *
     * @param string $alias     The alias
     * @param string $serviceId The service to return
     *
     * @return void
     */
    public function alias(string $alias, string $serviceId): void
    {
        $this->aliases[$alias] = $serviceId;
    }

    /**
     * Bind a service to the container.
     *
     * @param \Valkyrja\Container\Service $service The service model
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    public function bind(Service $service): void
    {
        // If there is no id
        if (null === $service->getId()) {
            // Throw a new exception
            throw new InvalidServiceIdException();
        }

        $this->services[$service->getId()] = $service;
    }

    /**
     * Bind a context to the container.
     *
     * @param string                      $serviceId   The service id
     * @param \Valkyrja\Container\Service $giveService The service to give
     * @param string|null                 $class       [optional] The context class
     * @param string|null                 $method      [optional] The context method
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidContextException
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    public function context(string $serviceId, Service $giveService, string $class = null, string $method = null): void
    {
        // If the context index is null then there's no context
        if (null === $contextIndex = $this->contextServiceId($serviceId, $class, $method)) {
            throw new InvalidContextException();
        }

        $giveService->setId($contextIndex);

        $this->bind($giveService);
    }

    /**
     * Bind a singleton to the container.
     *
     * @param string $serviceId The service
     * @param mixed  $singleton The singleton
     */
    public function singleton(string $serviceId, $singleton): void
    {
        $this->singletons[$serviceId] = $singleton;
    }

    /**
     * Check whether a given service exists.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function has(string $serviceId): bool
    {
        return isset($this->services[$serviceId]) || isset($this->aliases[$serviceId]);
    }

    /**
     * Check whether a given service has context.
     *
     * @param string $serviceId The service
     * @param string $class     [optional] The context class
     * @param string $method    [optional] The context method
     *
     * @return bool
     */
    public function hasContext(string $serviceId, string $class = null, string $method = null): bool
    {
        // If no class or method were passed then the index will be null so return false
        if (null === $contextIndex = $this->contextServiceId($serviceId, $class, $method)) {
            return false;
        }

        return isset($this->services[$contextIndex]);
    }

    /**
     * Check whether a given service is an alias.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function isAlias(string $serviceId): bool
    {
        return isset($this->aliases[$serviceId]);
    }

    /**
     * Check whether a given service is a singleton.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function isSingleton(string $serviceId): bool
    {
        return isset($this->singletons[$serviceId]);
    }

    /**
     * Get a service from the container.
     *
     * @param string $serviceId The service
     * @param array  $arguments [optional] The arguments
     * @param string $class     [optional] The context class
     * @param string $method    [optional] The context method
     *
     * @return mixed
     */
    public function get(string $serviceId, array $arguments = null, string $class = null, string $method = null)
    {
        // If there is a context set for this class/method
        if ($this->hasContext($serviceId, $class, $method)) {
            // Return that context
            return $this->get($this->contextServiceId($serviceId, $class, $method), $arguments);
        }

        // If the service is a singleton
        if ($this->isSingleton($serviceId)) {
            // Return the singleton
            return $this->singletons[$serviceId];
        }

        // If this service is an alias
        if ($this->isAlias($serviceId)) {
            // Return the appropriate service
            return $this->get($this->aliases[$serviceId], $arguments, $class, $method);
        }

        // If the service is in the container
        if ($this->has($serviceId)) {
            // Return the made service
            return $this->make($serviceId, $arguments);
        }

        // If there are no argument return a new object
        if (null === $arguments) {
            return new $serviceId;
        }

        // Return a new object with the arguments
        return new $serviceId(...$arguments);
    }

    /**
     * Make a service.
     *
     * @param string     $serviceId The service id
     * @param array|null $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function make(string $serviceId, array $arguments = null)
    {
        $service = $this->services[$serviceId];
        $arguments = $service->getDefaults() ?? $arguments;

        // Dispatch before make event
        // TODO: Implement Event Dispatch

        // Make the object by dispatching the service
        $made = $this->dispatchCallable($service, $arguments);

        // Dispatch after make event
        // TODO: Implement Event Dispatch

        // If the service is a singleton
        if ($service->isSingleton()) {
            // Set singleton
            $this->singleton($serviceId, $made);
        }

        return $made;
    }

    /**
     * Get the context service id.
     *
     * @param string $serviceId The service
     * @param string $class     [optional] The context class
     * @param string $method    [optional] The context method
     *
     * @return string
     */
    public function contextServiceId(string $serviceId, string $class = null, string $method = null):? string
    {
        // If there is no class or method there's no context set
        if (null === $class && null === $method) {
            return null;
        }

        $index = $serviceId . '@' . ($class ?? '');

        // If there is a method
        if (null !== $method) {
            // If there is a class
            if (null !== $class) {
                // Add the double colon to separate the method name and class
                $index .= '::';
            }

            // Append the method/function to the string
            $index .= $method;
        }

        // service@class
        // service@method
        // service@class::method
        return $index;
    }

    /**
     * Bootstrap the container.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    public function bootstrap(): void
    {
        $this->bootstrapAnnotationsParser();
        $this->bootstrapAnnotations();
        $this->bootstrapRequest();
        $this->bootstrapRequest();
        $this->bootstrapResponse();
        $this->bootstrapJsonResponse();
        $this->bootstrapRedirectResponse();
        $this->bootstrapResponseBuilder();
        $this->bootstrapRouter();
        $this->bootstrapRouteAnnotations();
        $this->bootstrapView();
        $this->bootstrapClient();
        $this->bootstrapLoggerInterface();
        $this->bootstrapLogger();
    }

    /**
     * Bootstrap the annotations parser.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapAnnotationsParser(): void
    {
        $this->bind(
            (new Service())
                ->setId(AnnotationsParserContract::class)
                ->setClass(AnnotationsParser::class)
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the annotations.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapAnnotations(): void
    {
        $this->bind(
            (new Service())
                ->setId(AnnotationsContract::class)
                ->setClass(Annotations::class)
                ->setDependencies([AnnotationsParserContract::class])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the request.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapRequest(): void
    {
        $this->bind(
            (new Service())
                ->setId(RequestContract::class)
                ->setClass(Request::class)
                ->setStaticMethod('createFromGlobals')
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the response.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapResponse(): void
    {
        $this->bind(
            (new Service())
                ->setId(ResponseContract::class)
                ->setClass(Response::class)
        );
    }

    /**
     * Bootstrap the json response.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapJsonResponse(): void
    {
        $this->bind(
            (new Service())
                ->setId(JsonResponseContract::class)
                ->setClass(JsonResponse::class)
        );
    }

    /**
     * Bootstrap the redirect response.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapRedirectResponse(): void
    {
        $this->bind(
            (new Service())
                ->setId(RedirectResponseContract::class)
                ->setClass(RedirectResponse::class)
        );
    }

    /**
     * Bootstrap the response builder.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapResponseBuilder(): void
    {
        $this->bind(
            (new Service())
                ->setId(ResponseBuilderContract::class)
                ->setClass(ResponseBuilder::class)
                ->setDependencies([Application::class])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the router.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapRouter(): void
    {
        $this->bind(
            (new Service())
                ->setId(RouterContract::class)
                ->setClass(Router::class)
                ->setDependencies([Application::class])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the route annotations.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapRouteAnnotations(): void
    {
        $this->bind(
            (new Service())
                ->setId(RouteAnnotationsContract::class)
                ->setClass(RouteAnnotations::class)
                ->setDependencies([AnnotationsParserContract::class])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the view.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapView(): void
    {
        $this->bind(
            (new Service())
                ->setId(ViewContract::class)
                ->setClass(View::class)
                ->setDependencies([Application::class])
        );
    }

    /**
     * Bootstrap the client.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapClient(): void
    {
        $this->bind(
            (new Service())
                ->setId(ClientContract::class)
                ->setClass(Client::class)
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the logger interface.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapLoggerInterface(): void
    {
        $app = $this->get(Application::class);

        $this->bind(
            (new Service())
                ->setId(LoggerInterface::class)
                ->setClass(MonologLogger::class)
                ->setDependencies([Application::class])
                ->setArguments([
                    $app->config()->logger->name,
                    [
                        (new Dispatch())
                            ->setClass(StreamHandler::class)
                            ->setArguments([
                                $app->config()->logger->filePath,
                                LogLevel::DEBUG,
                            ]),
                    ],
                ])
                ->setSingleton(true)
        );
    }

    /**
     * Bootstrap the logger.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    protected function bootstrapLogger(): void
    {
        $this->bind(
            (new Service())
                ->setId(LoggerContract::class)
                ->setClass(Logger::class)
                ->setDependencies([LoggerInterface::class])
                ->setSingleton(true)
        );
    }
}
