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

use Valkyrja\Annotations\AnnotationsImpl;
use Valkyrja\Annotations\AnnotationsParser;
use Valkyrja\Application;
use Valkyrja\Routing\Exceptions\InvalidRoutePath;
use Valkyrja\Routing\Route as RouterRoute;
use Valkyrja\Support\Providers\Provides;

/**
 * Class RouteAnnotations.
 *
 * @author Melech Mizrachi
 */
class RouteAnnotationsImpl extends AnnotationsImpl implements RouteAnnotations
{
    use Provides;

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
     * @throws \ReflectionException
     * @throws \Valkyrja\Routing\Exceptions\InvalidRoutePath
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
            // Set the route's properties
            $this->setRouteProperties($route);

            // If this route's class has annotations
            if ($classAnnotations = $this->classAnnotationsType($this->routeAnnotationType, $route->getClass())) {
                /** @var Route $annotation */
                // Iterate through all the annotations
                foreach ($classAnnotations as $annotation) {
                    // And set a new route with the controller defined annotation additions
                    $finalRoutes[] = $this->getRouteFromAnnotation($this->getControllerBuiltRoute($annotation, $route));
                }
            } else {
                // Validate the path before setting the route
                $route->setPath($this->validatePath($route->getPath()));

                // Otherwise just set the route in the final array
                $finalRoutes[] = $this->getRouteFromAnnotation($route);
            }
        }

        return $finalRoutes;
    }

    /**
     * Set the route properties from arguments.
     *
     * @param \Valkyrja\Routing\Annotations\Route $route
     *
     * @throws \ReflectionException
     * @throws \Valkyrja\Routing\Exceptions\InvalidRoutePath
     *
     * @return void
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
        $route->setMatches();

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
     * @param \Valkyrja\Routing\Annotations\Route $controllerRoute
     * @param \Valkyrja\Routing\Annotations\Route $route
     *
     * @return \Valkyrja\Routing\Annotations\Route
     */
    protected function getControllerBuiltRoute(Route $controllerRoute, Route $route): Route
    {
        $newRoute = clone $route;

        // If there is a base path for this controller
        if (null !== $controllerRoute->getPath()) {
            // Get the route's path
            $path           = $this->validatePath($route->getPath());
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
        if (false !== $controllerRoute->isDynamic()) {
            // Set the route to dynamic
            $newRoute->setDynamic(true);
        }

        // If the base is secure
        if (false !== $controllerRoute->isSecure()) {
            // Set the route to dynamic
            $newRoute->setSecure(true);
        }

        // If there is a base middleware collection for this controller
        if (null !== $controllerRoute->getMiddleware()) {
            // Merge the route's middleware and the controller's middleware
            // keeping the controller's middleware first
            $middleware = array_merge(
                $controllerRoute->getMiddleware(),
                $route->getMiddleware() ?? []
            );

            // Set the middleware in the route
            $newRoute->setMiddleware($middleware);
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
        // Trim slashes from the beginning and end of the path
        $path = trim($path, '/');

        // If the path only had a slash
        if (! $path) {
            // Return as just slash
            return '/';
        }

        // If the route doesn't begin with an optional or required group
        if ($path[0] !== '[' && $path[0] !== '<') {
            // Append a slash
            return '/' . $path;
        }

        return $path;
    }

    /**
     * Get a route from a route annotation.
     *
     * @param \Valkyrja\Routing\Annotations\Route $route The route annotation
     *
     * @return \Valkyrja\Routing\Route
     */
    protected function getRouteFromAnnotation(Route $route): RouterRoute
    {
        return (new RouterRoute())
            ->setPath($route->getPath())
            ->setRegex($route->getRegex())
            ->setParams($route->getParams())
            ->setSegments($route->getSegments())
            ->setRequestMethods($route->getRequestMethods())
            ->setSecure($route->isSecure())
            ->setDynamic($route->isDynamic())
            ->setId($route->getId())
            ->setName($route->getName())
            ->setClass($route->getClass())
            ->setProperty($route->getProperty())
            ->setMethod($route->getMethod())
            ->setStatic($route->isStatic())
            ->setFunction($route->getFunction())
            ->setMatches($route->getMatches())
            ->setDependencies($route->getDependencies())
            ->setArguments($route->getArguments());
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            RouteAnnotations::class,
        ];
    }

    /**
     * Bind the route annotations.
     *
     * @param \Valkyrja\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            RouteAnnotations::class,
            new static(
                $app->container()->getSingleton(AnnotationsParser::class)
            )
        );
    }
}
