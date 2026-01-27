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
use ReflectionMethod;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Attribute\Collector\Contract\CollectorContract as AttributeContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Routing\Attribute\Parameter;
use Valkyrja\Http\Routing\Attribute\Route as Attribute;
use Valkyrja\Http\Routing\Attribute\Route\Middleware;
use Valkyrja\Http\Routing\Attribute\Route\Name;
use Valkyrja\Http\Routing\Attribute\Route\Path;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod;
use Valkyrja\Http\Routing\Attribute\Route\RequestStruct;
use Valkyrja\Http\Routing\Attribute\Route\ResponseStruct;
use Valkyrja\Http\Routing\Collector\Contract\CollectorContract as Contract;
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\Parameter as DataParameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Processor\Contract\ProcessorContract;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Http\Routing\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Struct\Request\Contract\RequestStructContract;
use Valkyrja\Http\Struct\Response\Contract\ResponseStructContract;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Reflection\Reflector\Reflector;

use function array_column;
use function is_a;

class AttributeCollector implements Contract
{
    public function __construct(
        protected AttributeContract $attributes = new Collector(),
        protected ReflectorContract $reflection = new Reflector(),
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
        $routes = [];

        foreach ($classes as $class) {
            /** @var Attribute[] $attributes */
            $attributes = $this->attributes->forClassMembers($class, Attribute::class);

            // Iterate through all the members' attributes
            foreach ($attributes as $attribute) {
                /** @var ReflectionMethod $reflection */
                $reflection = $attribute->getReflection();
                $method     = $reflection->getName();
                $route      = $this->convertRouteAttributesToDataClass($attribute);

                $route = $this->updateDispatch($route, $class, $method);
                $route = $this->updatePath($route, $class, $method);
                $route = $this->updateName($route, $class, $method);
                $route = $this->updateMiddleware($route, $class, $method);
                $route = $this->updateRequestStruct($route, $class, $method);
                $route = $this->updateResponseStruct($route, $class, $method);
                $route = $this->updateRequestMethods($route, $class, $method);
                $route = $this->updateParameters($route, $class, $method);

                // And set a new route with the controller defined annotation additions
                $routes[] = $this->setRouteProperties($route);
            }
        }

        return $routes;
    }

    /**
     * @param class-string     $class  The class name
     * @param non-empty-string $method The method name
     */
    protected function updateDispatch(Route $route, string $class, string $method): Route
    {
        return $route->withDispatch(
            $route->getDispatch()
                ->withClass($class)
                ->withMethod($method)
        );
    }

    /**
     * Set the route properties from arguments.
     *
     * @throws ReflectionException
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

    /**
     * @param class-string     $class  The class name
     * @param non-empty-string $method The method name
     *
     * @throws ReflectionException
     */
    protected function updatePath(Route $route, string $class, string $method): Route
    {
        /** @var Path[] $classPaths */
        $classPaths = $this->attributes->forClass($class, Path::class);
        $routePaths = $this->attributes->forMethod($class, $method, Path::class);

        /** @var non-empty-string[] $classPath */
        $classPath = array_column($classPaths, 'value');

        if ($classPath !== []) {
            $route = $route->withPath($classPath[0] . $route->getPath());
        }

        /** @var non-empty-string[] $routePath */
        $routePath = array_column($routePaths, 'value');

        if ($routePath !== []) {
            $route = $route->withAddedPath($routePath[0]);
        }

        return $route;
    }

    /**
     * @param class-string     $class  The class name
     * @param non-empty-string $method The method name
     *
     * @throws ReflectionException
     */
    protected function updateName(Route $route, string $class, string $method): Route
    {
        /** @var Name[] $classNames */
        $classNames = $this->attributes->forClass($class, Name::class);
        $routeNames = $this->attributes->forMethod($class, $method, Name::class);

        /** @var non-empty-string[] $className */
        $className = array_column($classNames, 'value');

        if ($className !== []) {
            $route = $route->withName($className[0] . '.' . $route->getName());
        }

        /** @var non-empty-string[] $routeName */
        $routeName = array_column($routeNames, 'value');

        if ($routeName !== []) {
            $route = $route->withName($route->getName() . '.' . $routeName[0]);
        }

        return $route;
    }

    /**
     * @param class-string     $class  The class name
     * @param non-empty-string $method The method name
     *
     * @throws ReflectionException
     */
    protected function updateMiddleware(Route $route, string $class, string $method): Route
    {
        $middleware = $this->attributes->forMethod($class, $method, Middleware::class);

        /** @var class-string[] $middlewareClassNames */
        $middlewareClassNames = array_column($middleware, 'name');

        foreach ($middlewareClassNames as $middlewareClass) {
            $route = match (true) {
                is_a($middlewareClass, RouteMatchedMiddlewareContract::class, true)    => $route->withAddedRouteMatchedMiddleware(
                    $middlewareClass
                ),
                is_a($middlewareClass, RouteDispatchedMiddlewareContract::class, true) => $route->withAddedRouteDispatchedMiddleware(
                    $middlewareClass
                ),
                is_a($middlewareClass, ThrowableCaughtMiddlewareContract::class, true) => $route->withAddedThrowableCaughtMiddleware(
                    $middlewareClass
                ),
                is_a($middlewareClass, SendingResponseMiddlewareContract::class, true) => $route->withAddedSendingResponseMiddleware(
                    $middlewareClass
                ),
                is_a($middlewareClass, TerminatedMiddlewareContract::class, true)      => $route->withAddedTerminatedMiddleware(
                    $middlewareClass
                ),
                default                                                                => throw new InvalidArgumentException(
                    "Unsupported middleware class `$middlewareClass`"
                ),
            };
        }

        return $route;
    }

    /**
     * @param class-string     $class  The class name
     * @param non-empty-string $method The method name
     *
     * @throws ReflectionException
     */
    protected function updateRequestStruct(Route $route, string $class, string $method): Route
    {
        $requestStruct = $this->attributes->forMethod($class, $method, RequestStruct::class);

        /** @var class-string<RequestStructContract>[] $requestStructName */
        $requestStructName = array_column($requestStruct, 'name');

        if ($requestStructName !== []) {
            $route = $route->withRequestStruct($requestStructName[0]);
        }

        return $route;
    }

    /**
     * @param class-string     $class  The class name
     * @param non-empty-string $method The method name
     *
     * @throws ReflectionException
     */
    protected function updateResponseStruct(Route $route, string $class, string $method): Route
    {
        $responseStruct = $this->attributes->forMethod($class, $method, ResponseStruct::class);

        /** @var class-string<ResponseStructContract>[] $responseStructName */
        $responseStructName = array_column($responseStruct, 'name');

        if ($responseStructName !== []) {
            $route = $route->withResponseStruct($responseStructName[0]);
        }

        return $route;
    }

    /**
     * @param class-string     $class  The class name
     * @param non-empty-string $method The method name
     *
     * @throws ReflectionException
     */
    protected function updateRequestMethods(Route $route, string $class, string $method): Route
    {
        $requestMethods = $this->attributes->forMethod($class, $method, RequestMethod::class);

        foreach ($requestMethods as $requestMethod) {
            /** @psalm-suppress MixedArgument Unsure why Psalm doesn't realize that the requestMethods property is an array of RequestMethod enums */
            $route = $route->withAddedRequestMethods(...$requestMethod->requestMethods);
        }

        return $route;
    }

    /**
     * @param class-string     $class  The class name
     * @param non-empty-string $method The method name
     *
     * @throws ReflectionException
     */
    protected function updateParameters(Route $route, string $class, string $method): Route
    {
        $methodParameters = $this->attributes->forMethod($class, $method, Parameter::class);

        $route = $route->withParameters(
            ...$this->attributes->forMethodParameters($class, $method, Parameter::class),
            ...$methodParameters,
            ...$route->getParameters()
        );

        $parameterAttributes = $route->getParameters();
        $parameters          = [];

        foreach ($parameterAttributes as $parameterAttribute) {
            $parameters[] = $this->convertParameterAttributesToDataClass($parameterAttribute);
        }

        return $route->withParameters(...$parameters);
    }

    protected function convertRouteAttributesToDataClass(RouteContract $route): Route
    {
        return new Route(
            path: $route->getPath(),
            name: $route->getName(),
            dispatch: $route->getDispatch(),
            requestMethods: $route->getRequestMethods(),
            regex: $route->getRegex(),
            parameters: $route->getParameters(),
            routeMatchedMiddleware: $route->getRouteMatchedMiddleware(),
            routeDispatchedMiddleware: $route->getRouteDispatchedMiddleware(),
            throwableCaughtMiddleware: $route->getThrowableCaughtMiddleware(),
            sendingResponseMiddleware: $route->getSendingResponseMiddleware(),
            terminatedMiddleware: $route->getTerminatedMiddleware(),
            requestStruct: $route->getRequestStruct(),
            responseStruct: $route->getResponseStruct()
        );
    }

    protected function convertParameterAttributesToDataClass(ParameterContract $parameter): DataParameter
    {
        return new DataParameter(
            name: $parameter->getName(),
            regex: $parameter->getRegex(),
            cast: $parameter->getCast(),
            isOptional: $parameter->isOptional(),
            shouldCapture: $parameter->shouldCapture(),
            default: $parameter->getDefault()
        );
    }
}
