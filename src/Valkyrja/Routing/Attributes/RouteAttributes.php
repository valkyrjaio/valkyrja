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

namespace Valkyrja\Routing\Annotators;

use InvalidArgumentException;
use ReflectionException;
use Valkyrja\Attributes\Managers\Attributes;
use Valkyrja\Routing\Attributes\Route;
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
                    // If the class' members' had attributes
                    if (! empty($memberAttributes)) {
                        // Iterate through all the members' attributes
                        foreach ($memberAttributes as $routeAttribute) {
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

        return $routes;
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
        if (null !== $controllerAttribute->getMiddleware()) {
            // Merge the route's middleware and the controller's middleware
            // keeping the controller's middleware first
            $middleware = array_merge($controllerAttribute->getMiddleware(), $memberAttribute->getMiddleware() ?? []);

            // Set the middleware in the route
            $attribute->setMiddleware($middleware);
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
