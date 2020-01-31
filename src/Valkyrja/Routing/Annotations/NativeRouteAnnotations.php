<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Annotations;

use InvalidArgumentException;
use ReflectionException;
use Valkyrja\Annotation\AnnotationsParser;
use Valkyrja\Annotation\NativeAnnotations;
use Valkyrja\Application\Application;
use Valkyrja\Routing\Exceptions\InvalidRoutePath;
use Valkyrja\Routing\Route as RouterRoute;

/**
 * Class RouteAnnotations.
 *
 * @author Melech Mizrachi
 */
class NativeRouteAnnotations extends NativeAnnotations implements RouteAnnotations
{
    /**
     * The route annotation type.
     *
     * @var string
     */
    protected string $routeAnnotationType = 'Route';

    /**
     * Get routes.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     * @throws InvalidRoutePath
     * @throws InvalidArgumentException
     *
     * @return RouterRoute[]
     */
    public function getRoutes(string ...$classes): array
    {
        $routes = $this->getClassRoutes($classes);
        /** @var RouterRoute[] $finalRoutes */
        $finalRoutes = [];

        // Iterate through all the routes
        foreach ($routes as $route) {
            // Set the route's properties
            $this->setRouteProperties($route);

            $classAnnotations = $this->classAnnotationsType($this->routeAnnotationType, $route->getClass());

            // If this route's class has annotations
            if ($classAnnotations) {
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
     * @param Route $route
     *
     * @throws InvalidRoutePath
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setRouteProperties(Route $route): void
    {
        if (null === $route->getProperty()) {
            $methodReflection = $this->getMethodReflection(
                $route->getClass(),
                $route->getMethod() ?? '__construct'
            );

            // Set the dependencies
            $route->setDependencies($this->getDependencies(...$methodReflection->getParameters()));
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
     * @throws ReflectionException
     *
     * @return RouterRoute[]
     */
    protected function getClassRoutes(array $classes): array
    {
        /** @var RouterRoute[] $routes */
        $routes = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            $annotations = $this->classMembersAnnotationsType($this->routeAnnotationType, $class);

            // Get all the routes for each class and iterate through them
            foreach ($annotations as $annotation) {
                // Set the annotation in the routes list
                $routes[] = $annotation;
            }
        }

        return $routes;
    }

    /**
     * Get a new route with controller route additions.
     *
     * @param Route $controllerRoute
     * @param Route $route
     *
     * @return Route
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
            $middleware = array_merge($controllerRoute->getMiddleware(), $route->getMiddleware() ?? []);

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
     * @param Route $route The route annotation
     *
     * @throws InvalidArgumentException
     *
     * @return RouterRoute
     */
    protected function getRouteFromAnnotation(Route $route): RouterRoute
    {
        $routerRoute = new RouterRoute();

        $routerRoute
            ->setPath($route->getPath())
            ->setSegments($route->getSegments())
            ->setRedirectPath($route->getRedirectPath())
            ->setRedirectCode($route->getRedirectCode())
            ->setRegex($route->getRegex())
            ->setParams($route->getParams())
            ->setRequestMethods($route->getRequestMethods())
            ->setSecure($route->isSecure())
            ->setDynamic($route->isDynamic())
            ->setRedirect($route->isRedirect())
            ->setId($route->getId())
            ->setName($route->getName())
            ->setClass($route->getClass())
            ->setProperty($route->getProperty())
            ->setMethod($route->getMethod())
            ->setStatic($route->isStatic())
            ->setFunction($route->getFunction())
            ->setMatches($route->getMatches())
            ->setDependencies($route->getDependencies())
            ->setMiddleware($route->getMiddleware())
            ->setArguments($route->getArguments());

        return $routerRoute;
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
     * @param \Valkyrja\Application\Application $app The application
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
