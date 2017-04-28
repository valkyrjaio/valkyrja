<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container;

use Valkyrja\Container\Exceptions\InvalidContextException;
use Valkyrja\Container\Exceptions\InvalidServiceIdException;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Dispatcher\Dispatcher;

/**
 * Class Container
 *
 * @package Valkyrja\Container
 *
 * @author  Melech Mizrachi
 */
class Container implements ContainerContract
{
    use Dispatcher;

    /**
     * The aliases.
     *
     * @var string[]
     */
    protected $aliases = [];

    /**
     * The services.
     *
     * @var \Valkyrja\Container\Service[]
     */
    protected $services = [];

    /**
     * The singletons.
     *
     * @var array
     */
    protected $singletons = [];

    /**
     * Set an alias to the container.
     *
     * @param string $alias     The alias
     * @param string $serviceId The service to return
     *
     * @return void
     */
    public function alias(string $alias, string $serviceId): void
    {
        $this->aliases[$alias] = $serviceId;
    }

    /**
     * Bind a service to the container.
     *
     * @param \Valkyrja\Container\Service $service The service model
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     */
    public function bind(Service $service): void
    {
        // If there is no id
        if (null === $service->getId()) {
            // Throw a new exception
            throw new InvalidServiceIdException();
        }

        $this->verifyDispatch($service);

        $this->services[$service->getId()] = $service;
    }

    /**
     * Bind a context to the container.
     *
     * @param string                      $serviceId   The service id
     * @param \Valkyrja\Container\Service $giveService The service to give
     * @param string                      $context     The context
     * @param string                      $member      [optional] The context member
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidContextException
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidClosureException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidFunctionException
     * @throws \Valkyrja\Dispatcher\Exceptions\InvalidMethodException
     *
     * TODO: Update to use ContextService
     */
    public function context(string $serviceId, Service $giveService, string $context, string $member = null): void
    {
        // If the context index is null then there's no context
        if (null === $contextIndex = $this->contextServiceId($serviceId, $context, $member)) {
            throw new InvalidContextException();
        }

        $giveService->setId($contextIndex);

        $this->bind($giveService);
    }

    /**
     * Bind a singleton to the container.
     *
     * @param string $serviceId The service
     * @param mixed  $singleton The singleton
     */
    public function singleton(string $serviceId, $singleton): void
    {
        $this->singletons[$serviceId] = $singleton;
    }

    /**
     * Check whether a given service exists.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function has(string $serviceId): bool
    {
        return isset($this->services[$serviceId]) || isset($this->aliases[$serviceId]);
    }

    /**
     * Check whether a given service has context.
     *
     * @param string $serviceId The service
     * @param string $context   The context
     *                          class name || function name || variable name
     * @param string $member    [optional] The context member
     *                          method name || property name
     *
     * @return bool
     */
    public function hasContext(string $serviceId, string $context, string $member = null): bool
    {
        // If no class or method were passed then the index will be null so return false
        if (null === $contextIndex = $this->contextServiceId($serviceId, $context, $member)) {
            return false;
        }

        return isset($this->services[$contextIndex]);
    }

    /**
     * Check whether a given service is an alias.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function isAlias(string $serviceId): bool
    {
        return isset($this->aliases[$serviceId]);
    }

    /**
     * Check whether a given service is a singleton.
     *
     * @param string $serviceId The service
     *
     * @return bool
     */
    public function isSingleton(string $serviceId): bool
    {
        return isset($this->singletons[$serviceId]);
    }

    /**
     * Get a service from the container.
     *
     * @param string $serviceId The service
     * @param array  $arguments [optional] The arguments
     * @param string $context   [optional] The context
     *                          class name || function name || variable name
     * @param string $member    [optional] The context member
     *                          method name || property name
     *
     * @return mixed
     */
    public function get(string $serviceId, array $arguments = null, string $context = null, string $member = null)
    {
        // If there is a context set for this class/method
        if (null !== $context && $this->hasContext($serviceId, $context, $member)) {
            // Return that context
            return $this->get($this->contextServiceId($serviceId, $context, $member), $arguments);
        }

        // If the service is a singleton
        if ($this->isSingleton($serviceId)) {
            // Return the singleton
            return $this->singletons[$serviceId];
        }

        // If this service is an alias
        if ($this->isAlias($serviceId)) {
            // Return the appropriate service
            return $this->get($this->aliases[$serviceId], $arguments, $context, $member);
        }

        // If the service is in the container
        if ($this->has($serviceId)) {
            // Return the made service
            return $this->make($serviceId, $arguments);
        }

        // If there are no argument return a new object
        if (null === $arguments) {
            return new $serviceId;
        }

        // Return a new object with the arguments
        return new $serviceId(...$arguments);
    }

    /**
     * Make a service.
     *
     * @param string     $serviceId The service id
     * @param array|null $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function make(string $serviceId, array $arguments = null)
    {
        $service = $this->services[$serviceId];
        $arguments = $service->getDefaults() ?? $arguments;

        // Dispatch before make event
        events()->trigger('service.make', [$serviceId, $service, $arguments]);
        events()->trigger("service.make.{$serviceId}", [$service, $arguments]);

        // Make the object by dispatching the service
        $made = $this->dispatchCallable($service, $arguments);

        // Dispatch after make event
        events()->trigger('service.made', [$serviceId, $made]);
        events()->trigger("service.made.{$serviceId}", [$made]);

        // If the service is a singleton
        if ($service->isSingleton()) {
            events()->trigger('service.made.singleton', [$serviceId, $made]);
            events()->trigger("service.made.singleton.{$serviceId}", [$made]);
            // Set singleton
            $this->singleton($serviceId, $made);
        }

        return $made;
    }

    /**
     * Get the context service id.
     *
     * @param string $serviceId The service
     * @param string $context   The context
     *                          class name || function name || variable name
     * @param string $member    [optional] The context member
     *                          method name || property name
     *
     * @return string
     */
    public function contextServiceId(string $serviceId, string $context, string $member = null):? string
    {
        $index = $serviceId . '@' . ($context ?? '');

        // If there is a method
        if (null !== $member) {
            // If there is a class
            if (null !== $context) {
                // Add the double colon to separate the method name and class
                $index .= '::';
            }

            // Append the method/function to the string
            $index .= $member;
        }

        // service@class
        // service@method
        // service@class::method
        return $index;
    }

    /**
     * Setup the container.
     *
     * @return void
     *
     * @throws \Valkyrja\Container\Exceptions\InvalidServiceIdException
     */
    public function setup(): void
    {
        new BootstrapContainer($this);
    }
}
