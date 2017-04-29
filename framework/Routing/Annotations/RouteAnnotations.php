<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Annotations;

use Valkyrja\Contracts\Routing\Annotations\RouteAnnotations as RouteAnnotationsContract;
use Valkyrja\Annotations\Annotations;
use Valkyrja\Routing\Exceptions\InvalidRoutePath;
use Valkyrja\Routing\Route;

/**
 * Class RouteAnnotations
 *
 * @package Valkyrja\Routing\Annotations
 *
 * @author  Melech Mizrachi
 */
class RouteAnnotations extends Annotations implements RouteAnnotationsContract
{
    /**
     * The route annotation type.
     *
     * @var string
     */
    protected $routeAnnotationType = 'Route';

    /**
     * Get routes.
     *
     * @param string[] $classes The classes
     *
     * @return \Valkyrja\Routing\Route[]
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\Routing\Exceptions\InvalidRoutePath
     */
    public function getRoutes(string ...$classes): array
    {
        $routes = $this->getClassRoutes($classes);
        /** @var \Valkyrja\Routing\Route[] $finalRoutes */
        $finalRoutes = [];

        // Iterate through all the routes
        foreach ($routes as $route) {
            // Set the route's properties
            $this->setRouteProperties($route);

            // If this route's class has annotations
            if ($classAnnotations = $this->classAnnotationsType($this->routeAnnotationType, $route->getClass())) {
                /** @var Route $annotation */
                // Iterate through all the annotations
                foreach ($classAnnotations as $annotation) {
                    // And set a new route with the controller defined annotation additions
                    $finalRoutes[] = $this->getControllerBuiltRoute($annotation, $route);
                }
            }
            else {
                // Otherwise just set the route in the final array
                $finalRoutes[] = $route;
            }
        }

        return $finalRoutes;
    }

    /**
     * Set the route properties from arguments.
     *
     * @param \Valkyrja\Routing\Route $route
     *
     * @return void
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\Routing\Exceptions\InvalidRoutePath
     */
    protected function setRouteProperties(Route $route): void
    {
        if (null === $route->getProperty()) {
            $parameters = $this->getMethodReflection($route->getClass(), $route->getMethod() ?? '__construct')
                               ->getParameters();

            // Set the dependencies
            $route->setDependencies($this->getDependencies(...$parameters));
        }

        // Avoid having large arrays in cached routes file
        $route->setArguments();
        $route->setMatches();
        // Set the type to null since we already know this is a route
        $route->setType();

        if (null === $route->getPath()) {
            throw new InvalidRoutePath(
                'Invalid route name for route : '
                . $route->getClass()
                . '@' . $route->getMethod()
            );
        }
    }

    /**
     * Get all classes' routes.
     *
     * @param array $classes The classes
     *
     * @throws \ReflectionException
     * @return \Valkyrja\Routing\Route[]
     */
    protected function getClassRoutes(array $classes): array
    {
        /** @var \Valkyrja\Routing\Route[] $routes */
        $routes = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the routes for each class and iterate through them
            foreach ($this->classMembersAnnotationsType($this->routeAnnotationType, $class) as $annotation) {
                // Set the annotation in the routes list
                $routes[] = $annotation;
            }
        }

        return $routes;
    }

    /**
     * Get a new route with controller route additions.
     *
     * @param \Valkyrja\Routing\Route $controllerRoute
     * @param \Valkyrja\Routing\Route $route
     *
     * @return \Valkyrja\Routing\Route
     */
    protected function getControllerBuiltRoute(Route $controllerRoute, Route $route): Route
    {
        $newRoute = clone $route;

        // If there is a base path for this controller
        if (null !== $controllerRoute->getPath()) {
            // Get the route's path
            $path = $this->validatePath($route->getPath());
            $controllerPath = $this->validatePath($controllerRoute->getPath());

            // Set the path to the base path and route path
            $newRoute->setPath($this->validatePath($controllerPath . $path));
        }

        // If there is a base name for this controller
        if (null !== $controllerRoute->getName()) {
            // Set the name to the base name and route name
            $newRoute->setName($controllerRoute->getName() . '.' . $route->getName());
        }

        // If the base is dynamic
        if (false !== $controllerRoute->getDynamic()) {
            // Set the route to dynamic
            $newRoute->setDynamic(true);
        }

        // If the base is secure
        if (false !== $controllerRoute->getSecure()) {
            // Set the route to dynamic
            $newRoute->setSecure(true);
        }

        return $newRoute;
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
        return '/' . trim($path, '/');
    }
}
