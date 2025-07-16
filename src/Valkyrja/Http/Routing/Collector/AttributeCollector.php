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

namespace Valkyrja\Http\Routing\Collector;

use Override;
use ReflectionException;
use Valkyrja\Attribute\Attributes;
use Valkyrja\Attribute\Contract\Attributes as AttributeContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Routing\Attribute\Parameter;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Attribute\Route\Middleware;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod;
use Valkyrja\Http\Routing\Attribute\Route\RequestStruct;
use Valkyrja\Http\Routing\Attribute\Route\ResponseStruct;
use Valkyrja\Http\Routing\Collector\Contract\Collector as Contract;
use Valkyrja\Http\Routing\Data\Contract\Route as RouteContract;
use Valkyrja\Http\Routing\Exception\InvalidArgumentException;
use Valkyrja\Http\Routing\Processor\Contract\Processor as ProcessorContract;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Http\Struct\Request\Contract\RequestStruct as RequestStructContract;
use Valkyrja\Http\Struct\Response\Contract\ResponseStruct as ResponseStructContract;
use Valkyrja\Reflection\Contract\Reflection as ReflectionContract;
use Valkyrja\Reflection\Reflection;

use function array_column;

/**
 * Class AttributeCollector.
 *
 * @author Melech Mizrachi
 */
class AttributeCollector implements Contract
{
    public function __construct(
        protected AttributeContract $attributes = new Attributes(),
        protected ReflectionContract $reflection = new Reflection(),
        protected ProcessorContract $processor = new Processor()
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function getRoutes(string ...$classes): array
    {
        $routes     = [];
        $attributes = [];

        foreach ($classes as $class) {
            /** @var Route[] $memberAttributes */
            $memberAttributes = $this->attributes->forClassMembers($class, Route::class);

            // Iterate through all the members' attributes
            foreach ($memberAttributes as $routeAttribute) {
                $routeDispatch = $routeAttribute->getDispatch();

                $method              = $routeDispatch->getMethod();
                $routeParameters     = $this->attributes->forMethod($class, $method, Parameter::class);
                $routeMiddleware     = $this->attributes->forMethod($class, $method, Middleware::class);
                $routeRequestStruct  = $this->attributes->forMethod($class, $method, RequestStruct::class);
                $routeResponseStruct = $this->attributes->forMethod($class, $method, ResponseStruct::class);
                $requestMethods      = $this->attributes->forMethod($class, $method, RequestMethod::class);

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
                        default                                                        => throw new InvalidArgumentException(
                            "Unsupported middleware class `$middlewareClass`"
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
                    ...$this->attributes->forMethodParameters($class, $method, Parameter::class),
                    ...$routeParameters
                );

                // And set a new route with the controller defined annotation additions
                $attributes[] = $routeAttribute;
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

        $methodReflection = $this->reflection->forClassMethod($dispatch->getClass(), $dispatch->getMethod());
        // Set the dependencies
        $route = $route->withDispatch(
            $dispatch->withDependencies($this->reflection->getDependencies($methodReflection))
        );

        return $this->processor->route($route);
    }
}
