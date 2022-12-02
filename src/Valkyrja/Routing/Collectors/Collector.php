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
use Valkyrja\Http\Constants\RequestMethod;
use Valkyrja\Reflection\Facades\Reflector;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Collector as Contract;
use Valkyrja\Routing\Constants\HandleSplit;
use Valkyrja\Routing\Models\Route as RouteModel;
use Valkyrja\Routing\Route;
use Valkyrja\Support\Type\Str;

/**
 * Class Collector.
 *
 * @author Melech Mizrachi
 */
class Collector implements Contract
{
    /**
     * The route context.
     *
     * @var Route
     */
    protected Route $route;

    /**
     * The routes for this collector.
     *
     * @var Route[]
     */
    protected array $routes;

    /**
     * Collector constructor.
     *
     * @param Collection $collection The collection
     */
    public function __construct(
        protected Collection $collection
    ) {
        // Set the route
        $this->route = new RouteModel();
        // Set the routes to an array
        $this->routes = [];
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): self
    {
        return $this->with('withPath', $path);
    }

    /**
     * @inheritDoc
     */
    public function withController(string $controller): self
    {
        return $this->with('setClass', $controller);
    }

    /**
     * @inheritDoc
     */
    public function withName(string $name): self
    {
        return $this->with('withName', $name);
    }

    /**
     * @inheritDoc
     */
    public function withMiddleware(array $middleware): self
    {
        return $this->with('withMiddleware', $middleware);
    }

    /**
     * @inheritDoc
     */
    public function withSecure(bool $secure = true): self
    {
        return $this->with('setSecure', $secure);
    }

    /**
     * @inheritDoc
     */
    public function group(Closure $group): self
    {
        $group($this);

        $this->addRoutesToCollection();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $path, Closure|string $handler, string $name = null, bool $setDependencies = true): Route
    {
        return $this->makeRoute(
            [
                RequestMethod::GET,
                RequestMethod::HEAD,
            ],
            $path,
            $handler,
            $name,
            $setDependencies
        );
    }

    /**
     * @inheritDoc
     */
    public function post(string $path, Closure|string $handler, string $name = null, bool $setDependencies = true): Route
    {
        return $this->makeRoute([RequestMethod::POST], $path, $handler, $name, $setDependencies);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, Closure|string $handler, string $name = null, bool $setDependencies = true): Route
    {
        return $this->makeRoute([RequestMethod::PUT], $path, $handler, $name, $setDependencies);
    }

    /**
     * @inheritDoc
     */
    public function patch(string $path, Closure|string $handler, string $name = null, bool $setDependencies = true): Route
    {
        return $this->makeRoute([RequestMethod::PATCH], $path, $handler, $name, $setDependencies);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path, Closure|string $handler, string $name = null, bool $setDependencies = true): Route
    {
        return $this->makeRoute([RequestMethod::DELETE], $path, $handler, $name, $setDependencies);
    }

    /**
     * @inheritDoc
     */
    public function head(string $path, Closure|string $handler, string $name = null, bool $setDependencies = true): Route
    {
        return $this->makeRoute([RequestMethod::HEAD], $path, $handler, $name, $setDependencies);
    }

    /**
     * @inheritDoc
     */
    public function any(string $path, Closure|string $handler, string $name = null, bool $setDependencies = true): Route
    {
        return $this->makeRoute(RequestMethod::ANY, $path, $handler, $name, $setDependencies);
    }

    /**
     * @inheritDoc
     */
    public function redirect(string $path, string $to, array $methods = null, string $name = null): Route
    {
        return (new RouteModel())
            ->setPath($path)
            ->setTo($to)
            ->setName($name)
            ->setMethods($methods ?? [RequestMethod::GET, RequestMethod::HEAD]);
    }

    /**
     * With a new group instance to set the route method value.
     *
     * @param string $method
     * @param mixed  $value
     *
     * @return static
     */
    protected function with(string $method, mixed $value): self
    {
        // Create a new instance. We do not want the routes to come along for the ride
        $self = new $this();

        // Clone the current route so we don't override the current group's route
        $route = clone $this->route;
        // Set the new instance's route to the clone
        $self->route = $route;

        // Call the method with the value. In case of being extended we want to be as future proof as possible here
        // We normally expect the result to be a Route, but in case a new method if added by an extended version
        // of the collector we want to check that the result is an instance of Route before setting the route to it.
        // This is why the new instance's route isn't directly set to the results of the method call
        if (($result = $route->{$method}($value)) instanceof Route) {
            $self->route = $result;
        }

        return $self;
    }

    /**
     * @param array          $methods         The methods to set
     * @param string         $path            The path
     * @param Closure|string $handler         The handler
     * @param string|null    $name            [optional] The name of the route
     * @param bool           $setDependencies [optional] Whether to dynamically set dependencies
     *
     * @return Route
     */
    protected function makeRoute(
        array $methods,
        string $path,
        Closure|string $handler,
        string $name = null,
        bool $setDependencies = true
    ): Route {
        $route = clone $this->route;

        $route->withPath($path);

        if ($name !== null) {
            $route->withName($name);
        }

        $this->setRouteHandler($route, $handler);
        $route->setMethods($methods);

        if ($setDependencies) {
            $this->setDependencies($route);
        }

        $this->routes[] = $route;

        return $route;
    }

    /**
     * Set the route handler.
     *
     * @param Route          $route   The route
     * @param Closure|string $handler The handler
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    protected function setRouteHandler(Route $route, Closure|string $handler): void
    {
        if ($handler instanceof Closure) {
            $route->setClosure($handler);

            return;
        }

        $this->setRouteHandlerFromString($route, $handler);
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

        /** @var callable-string $handler */
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
            /** @var class-string $class */
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

    protected function addRoutesToCollection(): void
    {
        foreach ($this->routes as $route) {
            $this->collection->add($route);
        }
    }
}
