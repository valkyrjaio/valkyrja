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

namespace Valkyrja\Routing\Attribute;

use InvalidArgumentException;
use ReflectionException;
use Valkyrja\Attribute\Contract\Attributes as AttributeAttributes;
use Valkyrja\Reflection\Contract\Reflection;
use Valkyrja\Routing\Attribute as Contract;
use Valkyrja\Routing\Message\Contract\Message as RoutingMessage;
use Valkyrja\Routing\Processor\Contract\Processor;

/**
 * Class Attributes.
 *
 * @author Melech Mizrachi
 */
class Attributes implements Contract
{
    public function __construct(
        protected AttributeAttributes $attributes,
        protected Reflection $reflection,
        protected Processor $processor
    ) {
    }

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
            $classAttributes = $this->attributes->forClass($class, Route::class);
            /** @var Route[] $memberAttributes */
            $memberAttributes = $this->attributes->forClassMembers($class, Route::class);

            $finalAttributes = [];

            // If this class has attributes
            if (! empty($classAttributes)) {
                // Iterate through all the class attributes
                foreach ($classAttributes as $classAttribute) {
                    /** @var Parameter[] $mergedClassParameters */
                    $mergedClassParameters = [
                        ...$classAttribute->getParameters(),
                        ...$this->attributes->forClass($class, Parameter::class),
                    ];
                    $mergedClassMiddleware = [
                        ...($classAttribute->getMiddleware() ?? []),
                        ...array_column($this->attributes->forClass($class, Middleware::class), 'name'),
                    ];
                    /** @var class-string<RoutingMessage>[] $mergedClassMessages */
                    $mergedClassMessages = [
                        ...($classAttribute->getMessages() ?? []),
                        ...array_column($this->attributes->forClass($class, Message::class), 'name'),
                    ];

                    if (! empty($this->attributes->forClass($class, Secure::class))) {
                        $classAttribute->setSecure();
                    }

                    if (! empty($to = array_column($this->attributes->forClass($class, Redirect::class), 'to'))) {
                        $classAttribute->setTo($to[0]);
                    }

                    $classAttribute->setParameters($mergedClassParameters);
                    $classAttribute->setMiddleware($mergedClassMiddleware);
                    $classAttribute->setMessages($mergedClassMessages);

                    // If the class' members' had attributes
                    if (! empty($memberAttributes)) {
                        // Iterate through all the members' attributes
                        foreach ($memberAttributes as $routeAttribute) {
                            $routeParameters = [];
                            $routeMiddleware = [];
                            $routeMessages   = [];
                            $routeSecure     = [];
                            $routeRedirect   = [];

                            if (($property = $routeAttribute->getProperty()) !== null) {
                                $routeParameters = $this->attributes->forProperty($class, $property, Parameter::class);
                                $routeMiddleware = $this->attributes->forProperty($class, $property, Middleware::class);
                                $routeMessages   = $this->attributes->forProperty($class, $property, Message::class);
                                $routeSecure     = $this->attributes->forProperty($class, $property, Secure::class);
                                $routeRedirect   = $this->attributes->forProperty($class, $property, Redirect::class);
                            } elseif (($method = $routeAttribute->getMethod()) !== null) {
                                $routeParameters = $this->attributes->forMethod($class, $method, Parameter::class);
                                $routeMiddleware = $this->attributes->forMethod($class, $method, Middleware::class);
                                $routeMessages   = $this->attributes->forMethod($class, $method, Message::class);
                                $routeSecure     = $this->attributes->forMethod($class, $method, Secure::class);
                                $routeRedirect   = $this->attributes->forMethod($class, $method, Redirect::class);
                            }

                            /** @var Parameter[] $mergedPropertyParameters */
                            $mergedPropertyParameters = [
                                ...$routeAttribute->getParameters(),
                                ...$routeParameters,
                            ];
                            $mergedPropertyMiddleware = [
                                ...($routeAttribute->getMiddleware() ?? []),
                                ...array_column($routeMiddleware, 'name'),
                            ];
                            /** @var class-string<RoutingMessage>[] $mergedPropertyMessages */
                            $mergedPropertyMessages = [
                                ...($routeAttribute->getMessages() ?? []),
                                ...array_column($routeMessages, 'name'),
                            ];

                            if (! empty($routeSecure)) {
                                $routeAttribute->setSecure();
                            }

                            if (! empty($to = array_column($routeRedirect, 'to'))) {
                                $routeAttribute->setTo($to[0]);
                            }

                            $routeAttribute->setParameters($mergedPropertyParameters);
                            $routeAttribute->setMiddleware($mergedPropertyMiddleware);
                            $routeAttribute->setMessages($mergedPropertyMessages);

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
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setRouteProperties(Route $route): void
    {
        if (($class = $route->getClass()) === null) {
            throw new InvalidArgumentException('Invalid class defined in route.');
        }

        if (($method = $route->getMethod()) !== null) {
            $methodReflection = $this->reflection->forClassMethod($class, $method);
            // Set the dependencies
            $route->setDependencies($this->reflection->getDependencies($methodReflection));
        }

        // Avoid having large arrays in cached routes file
        $route->setMatches();

        $this->processor->route($route);
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

        // Get the route's path
        $path           = $this->getFilteredPath($memberAttribute->getPath());
        $controllerPath = $this->getFilteredPath($controllerAttribute->getPath());

        // Set the path to the base path and route path
        $attribute->setPath($this->getFilteredPath($controllerPath . $path));

        // If there is a base name for this controller
        if (($controllerName = $controllerAttribute->getName()) !== null) {
            // Set the name to the base name and route name
            $attribute->setName($controllerName . (($name = $memberAttribute->getName()) !== null ? '.' . $name : ''));
        }

        // If the base is dynamic
        if ($controllerAttribute->isDynamic()) {
            // Set the route to dynamic
            $attribute->setDynamic();
        }

        // If the base is secure
        if ($controllerAttribute->isSecure()) {
            // Set the route to secure
            $attribute->setSecure();
        }

        // If the base has a redirect
        if (($to = $controllerAttribute->getTo()) !== null) {
            // Set the route's redirect to path
            $attribute->setTo($to);
        }

        // If there is a base middleware collection for this controller
        if (($controllerMiddleware = $controllerAttribute->getMiddleware()) !== null) {
            // Merge the route's middleware and the controller's middleware
            // keeping the controller's middleware first
            $attribute->setMiddleware(
                [
                    ...$controllerMiddleware,
                    ...($memberAttribute->getMiddleware() ?? []),
                ]
            );
        }

        // If there is a base message collection for this controller
        if (($controllerMessages = $controllerAttribute->getMessages()) !== null) {
            // Merge the route's messages and the controller's messages
            // keeping the controller's messages first
            $attribute->setMessages(
                [
                    ...$controllerMessages,
                    ...($memberAttribute->getMessages() ?? []),
                ]
            );
        }

        // If there is a base parameters collection for this controller
        if (! empty($controllerParameters = $controllerAttribute->getParameters())) {
            // Merge the route's parameters and the controller's parameters
            // keeping the controller's parameters first
            $attribute->setParameters(
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
    protected function getFilteredPath(string $path): string
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
