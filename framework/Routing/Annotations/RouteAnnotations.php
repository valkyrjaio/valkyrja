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

use Valkyrja\Annotations\Annotations;
use Valkyrja\Routing\Route;

/**
 * Class RouteAnnotations
 *
 * @package Valkyrja\Routing\Annotations
 *
 * @author  Melech Mizrachi
 */
class RouteAnnotations extends Annotations
{
    /**
     * Get routes.
     *
     * @param string[] $classes The classes
     *
     * @return array
     */
    public function getRoutes(string ...$classes): array
    {
        /** @var \Valkyrja\Routing\Route[] $routes */
        $routes = [];
        /** @var \Valkyrja\Routing\Route[] $finalRoutes */
        $finalRoutes = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the routes for each class and iterate through them
            foreach ($this->methodsAnnotations($class) as $annotation) {
                // Set the annotation in the routes list
                $routes[] = $annotation;
            }
        }

        // Iterate through all the routes
        foreach ($routes as $route) {
            // Setup to find any injectable objects through the service container
            $dependencies = [];

            // Iterate through the method's parameters
            foreach ($this->getMethodReflection($route->getClass(), $route->getMethod())->getParameters() as $parameter) {
                // We only care for classes
                if ($parameter->getClass()) {
                    // Set the injectable in the array
                    $dependencies[] = $parameter->getClass()->getName();
                }
            }

            // Set the route's dependencies
            $route->setDependencies($dependencies);

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
        if (null !== $controllerPath = $controllerRoute->getPath()) {
            // Get the route's path
            $path = $route->getPath();

            // If this is the index
            if ('/' === $path) {
                // Set to blank so the final path will be just the base path
                $path = '';
            }
            // If the controller route is the index
            else if ('/' === $controllerPath) {
                // Set to blank so the final path won't start with double slash
                $controllerPath = '';
            }

            // Set the path to the base path and route path
            $newRoute->setPath($controllerPath . $path);
        }

        // If there is a base name for this controller
        if (null !== $controllerName = $controllerRoute->getName()) {
            $name = $controllerName . '.' . $route->getName();

            // Set the name to the base name and route name
            $newRoute->setName($name);
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
}
