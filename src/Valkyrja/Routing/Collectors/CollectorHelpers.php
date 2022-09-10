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

namespace Valkyrja\Routing\Collectors;

use Closure;
use InvalidArgumentException;
use Valkyrja\Reflection\Facades\Reflector;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Constants\HandleSplit;
use Valkyrja\Routing\Models\Route as RouteModel;
use Valkyrja\Routing\Route;
use Valkyrja\Support\Type\Str;

use function array_merge;
use function explode;
use function is_array;
use function is_string;
use function str_replace;

/**
 * Trait CollectorHelpers.
 *
 * @author Melech Mizrachi
 */
trait CollectorHelpers
{
    /**
     * The route context.
     *
     * @var Route|null
     */
    protected ?Route $route = null;

    /**
     * The collection service.
     *
     * @var Collection
     */
    protected Collection $collection;

    /**
     * Ensure a route context is set so we can chain 'with' group methods.
     *
     * @return void
     */
    protected function ensureRoute(): void
    {
        if (null === $this->route) {
            $this->route = new RouteModel();
        }
    }

    /**
     * With a new group instance to set the route method value.
     *
     * @param string $method
     * @param mixed  $value
     *
     * @return static
     */
    protected function withGroupableSelf(string $method, mixed $value): self
    {
        $self = clone $this;
        $self->ensureRoute();

        $route = clone $self->route;
        $route->{$method}($value);

        $method = "{$method}InRoute";
        $this->{$method}($route);

        $self->route = $route;

        return $self;
    }

    /**
     * Set a controller context in a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setPathInRoute(Route $route): void
    {
        $this->setPropertyInRoute($route, 'Path', true);
    }

    /**
     * Set a controller context in a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setClassInRoute(Route $route): void
    {
        // $this->setPropertyInRoute($route, 'Class');
    }

    /**
     * Set a controller context in a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setNameInRoute(Route $route): void
    {
        $this->setPropertyInRoute($route, 'Name', true);
    }

    /**
     * Set a controller context in a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setMiddlewareInRoute(Route $route): void
    {
        $this->setPropertyInRoute($route, 'Middleware', true);
    }

    /**
     * Set a secure context in a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setSecureInRoute(Route $route): void
    {
        // $this->setPropertyInRoute($route, 'Secure');
    }

    /**
     * Set a property in a route if it exists in the context route.
     *
     * @param Route  $route  The route
     * @param string $method The method to set
     * @param bool   $merge  [optional] Whether to merge the values
     *
     * @return void
     */
    protected function setPropertyInRoute(Route $route, string $method, bool $merge = false): void
    {
        if (null === $this->route) {
            return;
        }

        $getMethod = $method === 'Secure' ? "is{$method}" : "get{$method}";
        $setMethod = "set{$method}";

        if ($merge && $value = $this->route->{$getMethod}()) {
            $value = $this->mergePropertiesForRoute($value, $route->{$getMethod}(), $method === 'Name' ? '.' : '');

            $route->{$setMethod}($value);
        }
    }

    /**
     * @param string|array $value      The context route value
     * @param string|array $routeValue The route value
     * @param string       $glue       [optional] The glue
     *
     * @return string|array
     */
    protected function mergePropertiesForRoute($value, $routeValue, string $glue = '')
    {
        if (is_array($value) && is_array($routeValue)) {
            return array_merge($value, $routeValue);
        }

        if (is_string($value) && is_string($routeValue)) {
            return $value . $glue . $routeValue;
        }

        if ($value && ! $routeValue) {
            return $value;
        }

        return $routeValue;
    }

    /**
     * Get a route for a helper method.
     *
     * @param string         $path            The path
     * @param string|Closure $handler         The handler
     * @param string|null    $name            [optional] The name of the route
     * @param bool           $setDependencies [optional] Whether to dynamically set dependencies
     *
     * @throws InvalidArgumentException
     *
     * @return Route
     */
    protected function getRouteForHelper(
        string $path,
        $handler,
        string $name = null,
        bool $setDependencies = true
    ): Route {
        $route = $this->route ? clone $this->route : new RouteModel();

        $route->setPath($path);
        $route->setName($name);

        $this->setRouteHandler($route, $handler);
        $this->setPathInRoute($route);
        $this->setNameInRoute($route);

        if ($setDependencies) {
            $this->setDependencies($route);
        }

        return $route;
    }

    /**
     * @param array          $methods         The methods to set
     * @param string         $path            The path
     * @param string|Closure $handler         The handler
     * @param string|null    $name            [optional] The name of the route
     * @param bool           $setDependencies [optional] Whether to dynamically set dependencies
     *
     * @return Route
     */
    protected function setMethodsAndAddRoute(
        array $methods,
        string $path,
        $handler,
        string $name = null,
        bool $setDependencies = true
    ): Route {
        $route = $this->getRouteForHelper($path, $handler, $name, $setDependencies);

        $route->setMethods($methods);

        $this->collection->add($route);

        return $route;
    }

    /**
     * Set the route handler.
     *
     * @param Route          $route   The route
     * @param string|Closure $handler The handler
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function setRouteHandler(Route $route, $handler): void
    {
        $this->verifyHandler($handler);

        if ($handler instanceof Closure) {
            $route->setClosure($handler);

            return;
        }

        $this->setRouteHandlerFromString($route, $handler);
    }

    /**
     * Verify a handler.
     *
     * @param mixed $handler The handler
     *
     * @return void
     */
    protected function verifyHandler($handler): void
    {
        if (! is_string($handler) && ! ($handler instanceof Closure)) {
            throw new InvalidArgumentException('Invalid handler provided.');
        }
    }

    /**
     * Set the route handler from a string.
     *
     * @param Route  $route   The route
     * @param string $handler The handler
     *
     * @return void
     */
    protected function setRouteHandlerFromString(Route $route, string $handler): void
    {
        if (Str::contains($handler, HandleSplit::DEFAULT)) {
            $this->setRouteInstanceHandler($route, $handler);

            return;
        }

        if (Str::contains($handler, HandleSplit::STATIC)) {
            $this->setRouteStaticHandler($route, $handler);
            $route->setStatic();

            return;
        }

        $route->setFunction($handler);
    }

    /**
     * Set the instance route handler.
     *
     * @param Route  $route   The route
     * @param string $handler The handler
     *
     * @return void
     */
    protected function setRouteInstanceHandler(Route $route, string $handler): void
    {
        $this->setRouteHandlerSplit($route, $handler, HandleSplit::DEFAULT);
    }

    /**
     * Set the static route handler.
     *
     * @param Route  $route   The route
     * @param string $handler The handler
     *
     * @return void
     */
    protected function setRouteStaticHandler(Route $route, string $handler): void
    {
        $this->setRouteHandlerSplit($route, $handler, HandleSplit::STATIC);
    }

    /**
     * Set the static route handler.
     *
     * @param Route  $route     The route
     * @param string $handler   The handler
     * @param string $delimiter The delimiter
     *
     * @return void
     */
    protected function setRouteHandlerSplit(Route $route, string $handler, string $delimiter): void
    {
        [$class, $member] = explode($delimiter, $handler);

        if ($class) {
            $route->setClass($class);
        }

        $this->setRouteMember($route, $member);
    }

    /**
     * Set the route handler member.
     *
     * @param Route  $route  The route
     * @param string $member The member
     *
     * @return void
     */
    protected function setRouteMember(Route $route, string $member): void
    {
        if (Str::contains($member, '(')) {
            $member = str_replace('()', '', $member);

            $route->setMethod($member);

            return;
        }

        $route->setProperty($member);
    }

    /**
     * Set a route's dependencies.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function setDependencies(Route $route): void
    {
        if (null !== $route->getDependencies()) {
            return;
        }

        $route->setDependencies($this->getDependencies($route));
    }

    /**
     * Get a route's dependencies.
     *
     * @param Route $route The route
     *
     * @return array
     */
    protected function getDependencies(Route $route): array
    {
        $dependencies = [];

        if (($class = $route->getClass()) && ($method = $route->getMethod())) {
            $dependencies = Reflector::getDependencies(Reflector::getMethodReflection($class, $method));
        } elseif ($function = $route->getFunction()) {
            $dependencies = Reflector::getDependencies(Reflector::getFunctionReflection($function));
        } elseif ($closure = $route->getClosure()) {
            $dependencies = Reflector::getDependencies(Reflector::getClosureReflection($closure));
        }

        return $dependencies;
    }
}
