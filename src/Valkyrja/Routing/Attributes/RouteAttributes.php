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

namespace Valkyrja\Routing\Attributes;

use InvalidArgumentException;
use ReflectionException;
use Valkyrja\Attribute\Managers\Attributes;
use Valkyrja\Routing\Exceptions\InvalidRoutePath;
use Valkyrja\Routing\RouteAttributes as Contract;

/**
 * Class Processor.
 *
 * @author Melech Mizrachi
 */
class RouteAttributes extends Attributes implements Contract
{
    /**
     * @inheritDoc
     *
     * @throws InvalidRoutePath
     * @throws ReflectionException
     */
    public function getRoutes(string ...$classes): array
    {
        $routes = [];

        foreach ($classes as $class) {
            /** @var Route[] $classAttributes */
            $classAttributes = $this->forClass($class, Route::class);
            /** @var Route[] $memberAttributes */
            $memberAttributes = $this->forClassMembers($class, Route::class);

            $finalAttributes = [];

            // If this class has attributes
            if (! empty($classAttributes)) {
                // Iterate through all the class attributes
                foreach ($classAttributes as $classAttribute) {
                    $classAttribute->setParameters(
                        [
                            ...$classAttribute->getParameters(),
                            ...$this->forClass($class, Parameter::class),
                        ]
                    );
                    $classAttribute->setMiddleware(
                        [
                            ...($classAttribute->getMiddleware() ?? []),
                            ...array_column($this->forClass($class, Middleware::class), 'name'),
                        ]
                    );

                    // If the class' members' had attributes
                    if (! empty($memberAttributes)) {
                        // Iterate through all the members' attributes
                        foreach ($memberAttributes as $routeAttribute) {
                            $routeParameters = [];
                            $routeMiddleware = [];

                            if ($property = $routeAttribute->getProperty()) {
                                $routeParameters = $this->forProperty($class, $property, Parameter::class);
                                $routeMiddleware = $this->forProperty($class, $property, Middleware::class);
                            } elseif ($method = $routeAttribute->getMethod()) {
                                $routeParameters = $this->forMethod($class, $method, Parameter::class);
                                $routeMiddleware = $this->forMethod($class, $method, Middleware::class);
                            }

                            $routeAttribute->setParameters(
                                [
                                    ...$routeAttribute->getParameters(),
                                    ...$routeParameters,
                                ]
                            );

                            $routeAttribute->setMiddleware(
                                [
                                    ...($routeAttribute->getMiddleware() ?? []),
                                    ...array_column($routeMiddleware, 'name'),
                                ]
                            );

                            // And set a new route with the controller defined annotation additions
                            $finalAttributes[] = $this->getControllerBuiltRoute($classAttribute, $routeAttribute);
                        }
                    }

                    // Figure out if there should be an else that automatically sets routes from the class attributes
                }
            } else {
                $finalAttributes = $memberAttributes;
            }

            $routes = [
                ...$routes,
                ...$finalAttributes,
            ];
        }

        foreach ($routes as $route) {
            $this->setRouteProperties($route);
        }

        return $routes;
    }

    /**
     * Set the route properties from arguments.
     *
     * @param Route $route
     *
     * @throws InvalidRoutePath
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setRouteProperties(Route $route): void
    {
        if (! $route->getClass()) {
            throw new InvalidArgumentException('Invalid class defined in route.');
        }

        if ($route->getMethod() !== null) {
            $methodReflection = $this->reflector->getMethodReflection(
                $route->getClass(),
                $route->getMethod()
            );

            // Set the dependencies
            $route->setDependencies($this->reflector->getDependencies($methodReflection));
        }

        // Avoid having large arrays in cached routes file
        $route->setMatches();

        if ($route->getPath() === null) {
            throw new InvalidRoutePath(
                'Invalid route path for route : '
                . $route->getClass()
                . '@' . $route->getMethod()
            );
        }
    }

    /**
     * Get a new attribute with controller attribute additions.
     *
     * @param Route $controllerAttribute The controller route attribute
     * @param Route $memberAttribute     The member route attribute
     *
     * @return Route
     */
    protected function getControllerBuiltRoute(Route $controllerAttribute, Route $memberAttribute): Route
    {
        $attribute = clone $memberAttribute;

        if (! $memberAttribute->getPath()) {
            throw new InvalidArgumentException('Invalid path defined in route.');
        }

        // If there is a base path for this controller
        if (null !== $controllerAttribute->getPath()) {
            // Get the route's path
            $path           = $this->validatePath($memberAttribute->getPath());
            $controllerPath = $this->validatePath($controllerAttribute->getPath());

            // Set the path to the base path and route path
            $attribute->setPath($this->validatePath($controllerPath . $path));
        }

        // If there is a base name for this controller
        if (null !== $controllerAttribute->getName()) {
            // Set the name to the base name and route name
            $attribute->setName($controllerAttribute->getName() . '.' . $memberAttribute->getName());
        }

        // If the base is dynamic
        if ($controllerAttribute->isDynamic()) {
            // Set the route to dynamic
            $attribute->setDynamic();
        }

        // If the base is secure
        if ($controllerAttribute->isSecure()) {
            // Set the route to dynamic
            $attribute->setSecure();
        }

        // If there is a base middleware collection for this controller
        if (null !== $controllerMiddleware = $controllerAttribute->getMiddleware()) {
            // Merge the route's middleware and the controller's middleware
            // keeping the controller's middleware first
            $attribute->setMiddleware(
                [
                    ...$controllerMiddleware,
                    ...($memberAttribute->getMiddleware() ?? []),
                ]
            );
        }

        // If there is a base parameters collection for this controller
        if (! empty($controllerParameters = $controllerAttribute->getParameters())) {
            // Merge the route's parameters and the controller's parameters
            // keeping the controller's parameters first
            $attribute->setMiddleware(
                [
                    ...$controllerParameters,
                    ...$memberAttribute->getParameters(),
                ]
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
    protected function validatePath(string $path): string
    {
        // Trim slashes from the beginning and end of the path
        if (! $path = trim($path, '/')) {
            // If the path only had a slash return as just slash
            return '/';
        }

        // If the route doesn't begin with an optional or required group
        if ($path[0] !== '[' && $path[0] !== '<') {
            // Append a slash
            return '/' . $path;
        }

        return $path;
    }
}
