<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Routing\Annotation\Annotators;

use InvalidArgumentException;
use ReflectionException;
use Valkyrja\Annotation\Annotators\Annotator;
use Valkyrja\Application\Application;
use Valkyrja\Routing\Annotation\Enums\Annotation;
use Valkyrja\Routing\Annotation\Route;
use Valkyrja\Routing\Annotation\RouteAnnotator as RouteAnnotatorContract;
use Valkyrja\Routing\Exceptions\InvalidRoutePath;
use Valkyrja\Routing\Models\Route as RouteModel;
use Valkyrja\Routing\Route as RouteContract;

/**
 * Class RouteAnnotator.
 *
 * @author Melech Mizrachi
 */
class RouteAnnotator extends Annotator implements RouteAnnotatorContract
{
    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            RouteAnnotatorContract::class,
        ];
    }

    /**
     * Bind the route annotations.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->setSingleton(
            RouteAnnotatorContract::class,
            new static($app)
        );
    }

    /**
     * Get routes.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     * @throws InvalidRoutePath
     * @throws InvalidArgumentException
     *
     * @return RouteContract[]
     */
    public function getRoutes(string ...$classes): array
    {
        $routes = $this->getClassRoutes($classes);
        /** @var RouteContract[] $finalRoutes */
        $finalRoutes = [];

        // Iterate through all the routes
        foreach ($routes as $route) {
            if (! $route->getClass() || ! $route->getPath()) {
                throw new InvalidArgumentException('Invalid class or path defined in route.');
            }

            // Set the route's properties
            $this->setRouteProperties($route);

            // Get the class's annotations
            $classAnnotations = $this->getClassAnnotations($route->getClass());

            // If this route's class has annotations
            if (! empty($classAnnotations)) {
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
     * Get all classes' routes.
     *
     * @param array $classes The classes
     *
     * @throws ReflectionException
     *
     * @return RouteContract[]
     */
    protected function getClassRoutes(array $classes): array
    {
        /** @var RouteContract[] $routes */
        $routes = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the routes for each class and iterate through them
            foreach ($this->getClassMemberAnnotations($class) as $annotation) {
                // Set the annotation in the routes list
                $routes[] = $annotation;
            }
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
                'Invalid route path for route : '
                . $route->getClass()
                . '@' . $route->getMethod()
            );
        }
    }

    /**
     * Get class annotations
     *
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return array
     */
    protected function getClassAnnotations(string $class): array
    {
        return $this->filter()->filterAnnotationsByTypes(
            Annotation::getValidValues(),
            ...$this->classAnnotations($class)
        );
    }

    /**
     * Get a route from a route annotation.
     *
     * @param Route $route The route annotation
     *
     * @throws InvalidArgumentException
     *
     * @return RouteContract
     */
    protected function getRouteFromAnnotation(Route $route): RouteContract
    {
        return RouteModel::fromArray($route->asArray());
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

        if (! $route->getPath()) {
            throw new InvalidArgumentException('Invalid path defined in route.');
        }

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
        if ($controllerRoute->isDynamic()) {
            // Set the route to dynamic
            $newRoute->setDynamic(true);
        }

        // If the base is secure
        if ($controllerRoute->isSecure()) {
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

    /**
     * Get class member annotations
     *
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return array
     */
    protected function getClassMemberAnnotations(string $class): array
    {
        return $this->filter()->filterAnnotationsByTypes(
            Annotation::getValidValues(),
            ...$this->classMembersAnnotations($class)
        );
    }
}
