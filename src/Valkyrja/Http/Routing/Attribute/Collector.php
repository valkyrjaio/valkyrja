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

namespace Valkyrja\Http\Routing\Attribute;

use InvalidArgumentException;
use ReflectionException;
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Dispatcher\Data\Contract\ClassDispatch;
use Valkyrja\Dispatcher\Data\Contract\ConstantDispatch;
use Valkyrja\Dispatcher\Data\Contract\MethodDispatch;
use Valkyrja\Dispatcher\Data\Contract\PropertyDispatch;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Routing\Attribute\Contract\Collector as Contract;
use Valkyrja\Http\Routing\Attribute\Route\Middleware;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod;
use Valkyrja\Http\Routing\Attribute\Route\RequestStruct;
use Valkyrja\Http\Routing\Attribute\Route\ResponseStruct;
use Valkyrja\Http\Routing\Data\Contract\Route as RouteContract;
use Valkyrja\Http\Routing\Processor\Contract\Processor;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct as RequestStructContract;
use Valkyrja\Http\Struct\Response\Contract\ResponseStruct as ResponseStructContract;
use Valkyrja\Reflection\Contract\Reflection;

use function array_column;

/**
 * Class Collector.
 *
 * @author Melech Mizrachi
 */
class Collector implements Contract
{
    public function __construct(
        protected Attributes $attributes,
        protected Reflection $reflection,
        protected Processor $processor
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function getRoutes(string ...$classes): array
    {
        $routes     = [];
        $attributes = [];

        foreach ($classes as $class) {
            /** @var Route[] $classAttributes */
            $classAttributes = $this->attributes->forClass($class, Route::class);
            /** @var Route[] $memberAttributes */
            $memberAttributes = $this->attributes->forClassMembers($class, Route::class);

            // If this class has attributes
            if ($classAttributes !== []) {
                // Iterate through all the class attributes
                foreach ($classAttributes as $classAttribute) {
                    $classAttribute = $classAttribute->withParameters(
                        ...$classAttribute->getParameters(),
                        ...$this->attributes->forClass($class, Parameter::class)
                    );

                    /** @var class-string[] $middlewareClasses */
                    $middlewareClasses = array_column(
                        $this->attributes->forClass($class, Middleware::class),
                        'name'
                    );

                    foreach ($middlewareClasses as $middlewareClass) {
                        $classAttribute = match (true) {
                            is_a($middlewareClass, RouteMatchedMiddleware::class, true)    => $classAttribute->withAddedRouteMatchedMiddleware(
                                $middlewareClass
                            ),
                            is_a($middlewareClass, RouteDispatchedMiddleware::class, true) => $classAttribute->withAddedRouteDispatchedMiddleware(
                                $middlewareClass
                            ),
                            is_a($middlewareClass, ThrowableCaughtMiddleware::class, true) => $classAttribute->withAddedThrowableCaughtMiddleware(
                                $middlewareClass
                            ),
                            is_a($middlewareClass, SendingResponseMiddleware::class, true) => $classAttribute->withAddedSendingResponseMiddleware(
                                $middlewareClass
                            ),
                            is_a($middlewareClass, TerminatedMiddleware::class, true)      => $classAttribute->withAddedTerminatedMiddleware(
                                $middlewareClass
                            ),
                        };
                    }

                    /** @var class-string<RequestStructContract>[] $requestStruct */
                    $requestStruct = array_column(
                        $this->attributes->forClass($class, RequestStruct::class),
                        'name'
                    );

                    if ($requestStruct !== []) {
                        $classAttribute = $classAttribute->withRequestStruct($requestStruct[0]);
                    }

                    /** @var class-string<ResponseStructContract>[] $responseStruct */
                    $responseStruct = array_column(
                        $this->attributes->forClass($class, ResponseStruct::class),
                        'name'
                    );

                    if ($responseStruct !== []) {
                        $classAttribute = $classAttribute->withResponseStruct($responseStruct[0]);
                    }

                    // If the class' members' had attributes
                    if ($memberAttributes !== []) {
                        // Iterate through all the members' attributes
                        foreach ($memberAttributes as $routeAttribute) {
                            $routeParameters     = [];
                            $routeMiddleware     = [];
                            $routeRequestStruct  = [];
                            $routeResponseStruct = [];
                            $requestMethods      = [];

                            $routeDispatch = $routeAttribute->getDispatch();

                            if ($routeDispatch instanceof PropertyDispatch) {
                                $property            = $routeDispatch->getProperty();
                                $routeParameters     = $this->attributes->forProperty($class, $property, Parameter::class);
                                $routeMiddleware     = $this->attributes->forProperty($class, $property, Middleware::class);
                                $routeRequestStruct  = $this->attributes->forProperty($class, $property, RequestStruct::class);
                                $routeResponseStruct = $this->attributes->forProperty($class, $property, ResponseStruct::class);
                                $requestMethods      = $this->attributes->forProperty($class, $property, RequestMethod::class);
                            } elseif ($routeDispatch instanceof MethodDispatch) {
                                $method              = $routeDispatch->getMethod();
                                $routeParameters     = $this->attributes->forMethod($class, $method, Parameter::class);
                                $routeMiddleware     = $this->attributes->forMethod($class, $method, Middleware::class);
                                $routeRequestStruct  = $this->attributes->forMethod($class, $method, RequestStruct::class);
                                $routeResponseStruct = $this->attributes->forMethod($class, $method, ResponseStruct::class);
                                $requestMethods      = $this->attributes->forMethod($class, $method, RequestMethod::class);
                            }

                            /** @var class-string[] $middlewareClasses */
                            $middlewareClasses = array_column(
                                $routeMiddleware,
                                'name'
                            );

                            foreach ($middlewareClasses as $middlewareClass) {
                                $routeAttribute = match (true) {
                                    is_a($middlewareClass, RouteMatchedMiddleware::class, true)    => $routeAttribute->withAddedRouteMatchedMiddleware(
                                        $middlewareClass
                                    ),
                                    is_a($middlewareClass, RouteDispatchedMiddleware::class, true) => $routeAttribute->withAddedRouteDispatchedMiddleware(
                                        $middlewareClass
                                    ),
                                    is_a($middlewareClass, ThrowableCaughtMiddleware::class, true) => $routeAttribute->withAddedThrowableCaughtMiddleware(
                                        $middlewareClass
                                    ),
                                    is_a($middlewareClass, SendingResponseMiddleware::class, true) => $routeAttribute->withAddedSendingResponseMiddleware(
                                        $middlewareClass
                                    ),
                                    is_a($middlewareClass, TerminatedMiddleware::class, true)      => $routeAttribute->withAddedTerminatedMiddleware(
                                        $middlewareClass
                                    ),
                                };
                            }

                            foreach ($requestMethods as $requestMethod) {
                                /** @psalm-suppress MixedArgument Unsure why Psalm doesn't realize that the requestMethods property is an array of RequestMethod enums */
                                $routeAttribute = $routeAttribute->withAddedRequestMethods(...$requestMethod->requestMethods);
                            }

                            /** @var class-string<RequestStructContract>[] $requestStruct */
                            $requestStruct = array_column($routeRequestStruct, 'name');

                            if ($requestStruct !== []) {
                                $routeAttribute = $routeAttribute->withRequestStruct($requestStruct[0]);
                            }

                            /** @var class-string<ResponseStructContract>[] $responseStruct */
                            $responseStruct = array_column($routeResponseStruct, 'name');

                            if ($responseStruct !== []) {
                                $routeAttribute = $routeAttribute->withResponseStruct($responseStruct[0]);
                            }

                            $routeAttribute = $routeAttribute->withParameters(
                                ...$routeAttribute->getParameters(),
                                ...$routeParameters
                            );

                            // And set a new route with the controller defined annotation additions
                            $attributes[] = $this->getControllerBuiltRoute($classAttribute, $routeAttribute);
                        }
                    }
                    // Figure out if there should be an else that automatically sets routes from the class attributes
                }
            }
        }

        foreach ($attributes as $attribute) {
            $attribute = $this->setRouteProperties($attribute);

            $routes[] = new \Valkyrja\Http\Routing\Data\Route(
                path: $attribute->getPath(),
                name: $attribute->getName(),
                dispatch: $attribute->getDispatch(),
                requestMethods: $attribute->getRequestMethods(),
                regex: $attribute->getRegex(),
                parameters: $attribute->getParameters(),
                routeMatchedMiddleware: $attribute->getRouteMatchedMiddleware(),
                routeDispatchedMiddleware: $attribute->getRouteDispatchedMiddleware(),
                throwableCaughtMiddleware: $attribute->getThrowableCaughtMiddleware(),
                sendingResponseMiddleware: $attribute->getSendingResponseMiddleware(),
                terminatedMiddleware: $attribute->getTerminatedMiddleware(),
                requestStruct: $attribute->getRequestStruct(),
                responseStruct: $attribute->getResponseStruct()
            );
        }

        return $routes;
    }

    /**
     * Set the route properties from arguments.
     *
     * @param RouteContract $route
     *
     * @throws ReflectionException
     *
     * @return RouteContract
     */
    protected function setRouteProperties(RouteContract $route): RouteContract
    {
        $dispatch = $route->getDispatch();

        if (! $dispatch instanceof ClassDispatch && ! $dispatch instanceof ConstantDispatch) {
            throw new InvalidArgumentException('Invalid class defined in route.');
        }

        if ($dispatch instanceof MethodDispatch) {
            $methodReflection = $this->reflection->forClassMethod($dispatch->getClass(), $dispatch->getMethod());
            // Set the dependencies
            $route = $route->withDispatch(
                $dispatch->withDependencies($this->reflection->getDependencies($methodReflection))
            );
        }

        return $this->processor->route($route);
    }

    /**
     * Get a new attribute with controller attribute additions.
     *
     * @param RouteContract $controllerAttribute The controller route attribute
     * @param RouteContract $memberAttribute     The member route attribute
     *
     * @return RouteContract
     */
    protected function getControllerBuiltRoute(RouteContract $controllerAttribute, RouteContract $memberAttribute): RouteContract
    {
        $attribute = clone $memberAttribute;

        // Get the route's path
        $path           = $this->getFilteredPath($memberAttribute->getPath());
        $controllerPath = $this->getFilteredPath($controllerAttribute->getPath());
        $controllerName = $controllerAttribute->getName();

        // Set the path to the base path and route path
        $attribute = $attribute->withPath($this->getFilteredPath($controllerPath . $path));
        $attribute = $attribute->withName($controllerName . '.' . $memberAttribute->getName());

        $attribute = $attribute->withAddedRouteMatchedMiddleware(...$controllerAttribute->getRouteMatchedMiddleware());
        $attribute = $attribute->withAddedRouteDispatchedMiddleware(...$controllerAttribute->getRouteDispatchedMiddleware());
        $attribute = $attribute->withAddedThrowableCaughtMiddleware(...$controllerAttribute->getThrowableCaughtMiddleware());
        $attribute = $attribute->withAddedSendingResponseMiddleware(...$controllerAttribute->getSendingResponseMiddleware());
        $attribute = $attribute->withAddedTerminatedMiddleware(...$controllerAttribute->getTerminatedMiddleware());

        $controllerRequestStruct = $controllerAttribute->getRequestStruct();

        // If there is a base message collection for this controller
        if ($controllerRequestStruct !== null) {
            // Merge the route's messages and the controller's messages
            // keeping the controller's messages first
            $attribute = $attribute->withRequestStruct($controllerRequestStruct);
        }

        $controllerResponseStruct = $controllerAttribute->getResponseStruct();

        // If there is a base message collection for this controller
        if ($controllerResponseStruct !== null) {
            // Merge the route's messages and the controller's messages
            // keeping the controller's messages first
            $attribute = $attribute->withResponseStruct($controllerResponseStruct);
        }

        $controllerParameters = $controllerAttribute->getParameters();

        // If there is a base parameters collection for this controller
        if ($controllerParameters !== []) {
            // Merge the route's parameters and the controller's parameters
            // keeping the controller's parameters first
            $attribute = $attribute->withParameters(
                ...$controllerParameters,
                ...$memberAttribute->getParameters()
            );
        }

        return $attribute;
    }

    /**
     * Validate a path.
     *
     * @param string $path The path
     *
     * @return string
     */
    protected function getFilteredPath(string $path): string
    {
        // Trim slashes from the beginning and end of the path
        if (! $path = trim($path, '/')) {
            // If the path only had a slash return as just slash
            return '/';
        }

        return $path;
    }
}
