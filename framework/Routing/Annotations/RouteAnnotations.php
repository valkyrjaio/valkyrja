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
     * Get routes.
     *
     * @param string[] $classes The classes
     *
     * @return \Valkyrja\Routing\Route[]
     */
    public function getRoutes(string ...$classes): array
    {
        $routes = $this->getClassRoutes($classes);
        /** @var \Valkyrja\Routing\Route[] $finalRoutes */
        $finalRoutes = [];

        // Iterate through all the routes
        foreach ($routes as $route) {
            // Set the route's dependencies
            $route->setDependencies(
                $this->getDependencies(
                    $this->getMethodReflection($route->getClass(), $route->getMethod())
                         ->getParameters()
                )
            );

            // If this route's class has annotations
            if ($classAnnotations = $this->classAnnotations($route->getClass())) {
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
     * Get all classes' routes.
     *
     * @param array $classes The classes
     *
     * @return \Valkyrja\Routing\Route[]
     */
    protected function getClassRoutes(array $classes): array
    {
        /** @var \Valkyrja\Routing\Route[] $routes */
        $routes = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the routes for each class and iterate through them
            foreach ($this->methodsAnnotations($class) as $annotation) {
                // Set the annotation in the routes list
                $routes[] = $annotation;
            }
        }

        return $routes;
    }

    /**
     * Get dependencies from parameters.
     *
     * @param array $parameters The parameters
     *
     * @return array
     */
    protected function getDependencies(array $parameters): array
    {
        // Setup to find any injectable objects through the service container
        $dependencies = [];

        // Iterate through the method's parameters
        foreach ($parameters as $parameter) {
            // We only care for classes
            if ($parameter->getClass()) {
                // Set the injectable in the array
                $dependencies[] = $parameter->getClass()->getName();
            }
        }

        return $dependencies;
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
