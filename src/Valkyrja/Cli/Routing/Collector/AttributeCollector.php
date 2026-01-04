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

namespace Valkyrja\Cli\Routing\Collector;

use Override;
use ReflectionException;
use Valkyrja\Attribute\Collector\Contract\CollectorContract;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Routing\Attribute\ArgumentParameter as ArgumentAttribute;
use Valkyrja\Cli\Routing\Attribute\OptionParameter as OptionAttribute;
use Valkyrja\Cli\Routing\Attribute\Route as Attribute;
use Valkyrja\Cli\Routing\Attribute\Route\Middleware;
use Valkyrja\Cli\Routing\Attribute\Route\Name;
use Valkyrja\Cli\Routing\Collector\Contract\CollectorContract as Contract;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\ArgumentParameterContract;
use Valkyrja\Cli\Routing\Data\Contract\OptionParameterContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Cli\Routing\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;

use function array_column;
use function array_merge;
use function is_a;

class AttributeCollector implements Contract
{
    public function __construct(
        protected CollectorContract $attributes,
        protected ReflectorContract $reflection,
    ) {
    }

    /**
     * Get the routes.
     *
     * @param class-string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return RouteContract[]
     */
    #[Override]
    public function getRoutes(string ...$classes): array
    {
        $routes = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            /** @var Attribute[] $attributes */
            $attributes = $this->attributes->forClassAndMembers($class, Attribute::class);

            // Get all the attributes for each class and iterate through them
            foreach ($attributes as $attribute) {
                $method = $attribute->getDispatch()->getMethod();
                $route  = $this->convertAttributeToData($attribute);

                $route = $this->updateName($route, $class, $method);
                $route = $this->updateMiddleware($route, $class, $method);
                $route = $this->updateArguments($route, $class, $method);
                $route = $this->updateOptions($route, $class, $method);

                $routes[] = $this->setRouteProperties($route);
            }
        }

        return $routes;
    }

    /**
     * Get a command from an attribute.
     *
     * @param Route $route The attribute
     *
     * @throws ReflectionException
     */
    protected function setRouteProperties(Route $route): Route
    {
        $dispatch = $route->getDispatch();

        $methodReflection = $this->reflection->forClassMethod($dispatch->getClass(), $dispatch->getMethod());
        $dependencies     = $this->reflection->getDependencies($methodReflection);

        return $route
            ->withDispatch($route->getDispatch()->withDependencies($dependencies))
            ->withArguments(...$route->getArguments())
            ->withOptions(...$route->getOptions());
    }

    /**
     * @param class-string     $class  The class name
     * @param non-empty-string $method The method name
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
                is_a($middlewareClass, RouteMatchedMiddlewareContract::class, true)    => $route->withAddedCommandMatchedMiddleware(
                    $middlewareClass
                ),
                is_a($middlewareClass, RouteDispatchedMiddlewareContract::class, true) => $route->withAddedCommandDispatchedMiddleware(
                    $middlewareClass
                ),
                is_a($middlewareClass, ThrowableCaughtMiddlewareContract::class, true) => $route->withAddedThrowableCaughtMiddleware(
                    $middlewareClass
                ),
                is_a($middlewareClass, ExitedMiddlewareContract::class, true)          => $route->withAddedExitedMiddleware(
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
     */
    protected function updateArguments(Route $route, string $class, string $method): Route
    {
        $methodParameters = $this->attributes->forMethod($class, $method, ArgumentAttribute::class);

        $route = $route->withArguments(
            ...$this->attributes->forMethodParameters($class, $method, ArgumentAttribute::class),
            ...$methodParameters,
            ...$route->getArguments()
        );

        $parameterAttributes = $route->getArguments();
        $parameters          = [];

        foreach ($parameterAttributes as $parameterAttribute) {
            $parameters[] = $this->convertArgumentAttributeToData($parameterAttribute);
        }

        return $route->withArguments(...$parameters);
    }

    /**
     * @param class-string     $class  The class name
     * @param non-empty-string $method The method name
     */
    protected function updateOptions(Route $route, string $class, string $method): Route
    {
        $methodParameters = $this->attributes->forMethod($class, $method, OptionAttribute::class);

        $route = $route->withOptions(
            ...$this->attributes->forMethodParameters($class, $method, OptionAttribute::class),
            ...$methodParameters,
            ...$route->getOptions()
        );

        $parameterAttributes = $route->getOptions();
        $parameters          = [];

        foreach ($parameterAttributes as $parameterAttribute) {
            $parameters[] = $this->convertOptionAttributeToData($parameterAttribute);
        }

        return $route->withOptions(...$parameters);
    }

    protected function convertAttributeToData(RouteContract $route): Route
    {
        return new Route(
            name: $route->getName(),
            description: $route->getDescription(),
            helpText: $route->getHelpText(),
            dispatch: $route->getDispatch(),
            commandMatchedMiddleware: $route->getCommandMatchedMiddleware(),
            commandDispatchedMiddleware: $route->getCommandDispatchedMiddleware(),
            throwableCaughtMiddleware: $route->getThrowableCaughtMiddleware(),
            exitedMiddleware: $route->getExitedMiddleware(),
            parameters: array_merge($route->getArguments(), $route->getOptions()),
        );
    }

    protected function convertArgumentAttributeToData(ArgumentParameterContract $parameter): ArgumentParameter
    {
        return new ArgumentParameter(
            name: $parameter->getName(),
            description: $parameter->getDescription(),
            cast: $parameter->getCast(),
            mode: $parameter->getMode(),
            valueMode: $parameter->getValueMode(),
        );
    }

    protected function convertOptionAttributeToData(OptionParameterContract $parameter): OptionParameter
    {
        return new OptionParameter(
            name: $parameter->getName(),
            description: $parameter->getDescription(),
            valueDisplayName: $parameter->getValueDisplayName(),
            cast: $parameter->getCast(),
            defaultValue: $parameter->getDefaultValue(),
            shortNames: $parameter->getShortNames(),
            validValues: $parameter->getValidValues(),
            options: $parameter->getOptions(),
            mode: $parameter->getMode(),
            valueMode: $parameter->getValueMode(),
        );
    }
}
