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

namespace Valkyrja\Container\Dispatchers;

use Valkyrja\Application\Application;
use Valkyrja\Container\Cacheables\ContainerCacheable;
use Valkyrja\Container\Container as ContainerContract;
use Valkyrja\Container\Events\ServiceMade;
use Valkyrja\Container\Events\ServiceMadeSingleton;
use Valkyrja\Container\Events\ServiceMake;
use Valkyrja\Container\Exceptions\EndlessContextLoopException;
use Valkyrja\Container\Exceptions\InvalidContextException;
use Valkyrja\Container\Exceptions\InvalidServiceIdException;
use Valkyrja\Container\Models\Service as ServiceModel;
use Valkyrja\Container\Service;
use Valkyrja\Container\ServiceContext;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;

/**
 * Class Container.
 *
 * @author Melech Mizrachi
 */
class Container implements ContainerContract
{
    use ContainerCacheable;

    /**
     * The singletons.
     *
     * @var array
     */
    protected static array $singletons = [];

    /**
     * Container constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

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
        self::$aliases[$alias] = $serviceId;
    }

    /**
     * Bind a service to the container.
     *
     * @param Service $service The service model
     * @param bool    $verify  [optional] Whether to verify the service
     *
     * @throws InvalidClosureException
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     * @throws InvalidServiceIdException
     *
     * @return void
     */
    public function bind(Service $service, bool $verify = true): void
    {
        // If there is no id
        if (null === $service->getId()) {
            // Throw a new exception
            throw new InvalidServiceIdException('Invalid service id provided.');
        }

        // If we should verify the dispatch
        if ($verify) {
            // Then verify it
            $this->app->dispatcher()->verifyDispatch($service);
        }

        self::$services[$service->getId()] = $service;
    }

    /**
     * Bind a context to the container.
     *
     * @param ServiceContext $serviceContext The context service
     *
     * @throws InvalidContextException
     * @throws EndlessContextLoopException
     * @throws InvalidServiceIdException
     * @throws InvalidClosureException
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     *
     * @return void
     */
    public function context(ServiceContext $serviceContext): void
    {
        $context        = $serviceContext->getClass() ?? $serviceContext->getFunction();
        $member         = $serviceContext->getMethod() ?? $serviceContext->getProperty();
        $contextContext = $serviceContext->getContextClass() ?? $serviceContext->getContextFunction();

        // If the context index is null then there's no context
        if (null === $context || null === $serviceContext->getId()) {
            throw new InvalidContextException('Invalid context.');
        }

        // If the context is the same as the end service dispatch and the
        // dispatch isn't static throw an error to disallow this kind of
        // context as it will create an endless loop where the dispatcher
        // will attempt to create the callable with the dependencies and the
        // hasContext check in Container::get() will keep catching it
        if ($context === $contextContext && ! $serviceContext->isStatic()) {
            throw new EndlessContextLoopException(
                'This kind of context will create'
                . 'an endless loop where the dispatcher will attempt to create the '
                . 'callable with the dependencies and the hasContext check in '
                . 'Container::get() will keep catching it: '
                . $this->contextServiceId(
                    $serviceContext->getId(),
                    $context,
                    $member
                )
            );
        }

        $service = $this->getServiceFromContext($serviceContext, $context, $member);

        $this->bind($service);
    }

    /**
     * Get a service model from a context model.
     *
     * @param ServiceContext $serviceContext The service context
     * @param string         $context        [optional] The context class or function
     * @param string         $member         [optional] The member
     *
     * @return Service
     */
    protected function getServiceFromContext(
        ServiceContext $serviceContext,
        string $context = null,
        string $member = null
    ): Service {
        $service   = new ServiceModel();
        $serviceId = $this->contextServiceId($serviceContext->getId(), $context, $member);

        $service
            ->setId($serviceId)
            ->setSingleton($serviceContext->isSingleton())
            ->setDefaults($serviceContext->getDefaults())
            ->setName($serviceContext->getName())
            ->setClass($serviceContext->getContextClass())
            ->setProperty($serviceContext->getContextProperty())
            ->setMethod($serviceContext->getContextMethod())
            ->setFunction($serviceContext->getContextFunction())
            ->setClosure($serviceContext->getContextClosure())
            ->setArguments($serviceContext->getArguments())
            ->setDependencies($serviceContext->getDependencies())
            ->setStatic($serviceContext->isStatic());

        return $service;
    }

    /**
     * Bind a singleton to the container.
     *
     * @param string $serviceId The service
     * @param mixed  $singleton The singleton
     */
    public function singleton(string $serviceId, $singleton): void
    {
        self::$singletons[$serviceId] = $singleton;
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
        return isset(self::$services[$serviceId]) || isset(self::$aliases[$serviceId]);
    }

    /**
     * Check whether a given service has context.
     *
     * @param string $serviceId The service
     * @param string $context   The context class name || function name || variable name
     * @param string $member    [optional] The context member method name || property name
     *
     * @return bool
     */
    public function hasContext(string $serviceId, string $context, string $member = null): bool
    {
        $contextIndex = $this->contextServiceId($serviceId, $context, $member);

        return isset(self::$services[$contextIndex]);
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
        return isset(self::$aliases[$serviceId]);
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
        return isset(self::$singletons[$serviceId]);
    }

    /**
     * Get a service from the container.
     *
     * @param string $serviceId The service
     * @param array  $arguments [optional] The arguments
     * @param string $context   [optional] The context class name || function name || variable name
     * @param string $member    [optional] The context member method name || property name
     *
     * @return mixed
     */
    public function get(string $serviceId, array $arguments = null, string $context = null, string $member = null)
    {
        // If there is a context set for this context and member combination
        if (null !== $context && $this->hasContext($serviceId, $context, $member)) {
            // Return that context
            return $this->get($this->contextServiceId($serviceId, $context, $member), $arguments);
        }

        // If there is a context set for this context only
        if (null !== $context && $this->hasContext($serviceId, $context)) {
            // Return that context
            return $this->get($this->contextServiceId($serviceId, $context), $arguments);
        }

        // If the service is a singleton
        if ($this->isSingleton($serviceId)) {
            // Return the singleton
            return $this->getSingleton($serviceId);
        }

        // If this service is an alias
        if ($this->isAlias($serviceId)) {
            // Return the appropriate service
            return $this->get(self::$aliases[$serviceId], $arguments, $context, $member);
        }

        // If the service is in the container
        if ($this->has($serviceId)) {
            // Return the made service
            return $this->make($serviceId, $arguments);
        }

        // Check if the service id is provided by a deferred service provider
        if ($this->isProvided($serviceId)) {
            return $this->getProvided($serviceId, $arguments, $context, $member);
        }

        // If there are no argument return a new object
        if (null === $arguments) {
            return new $serviceId();
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
        $service   = self::$services[$serviceId];
        $arguments = $service->getDefaults() ?? $arguments;

        // Dispatch before make event
        $this->app->events()->trigger(ServiceMake::class, [$serviceId, $service, $arguments]);

        // Make the object by dispatching the service
        $made = $this->app->dispatcher()->dispatch($service, $arguments);

        // Dispatch after make event
        $this->app->events()->trigger(ServiceMade::class, [$serviceId, $made]);

        // If the service is a singleton
        if ($service->isSingleton()) {
            $this->app->events()->trigger(ServiceMadeSingleton::class, [$serviceId, $made]);
            // Set singleton
            $this->singleton($serviceId, $made);
        }

        return $made;
    }

    /**
     * Get a singleton from the container.
     *
     * @param string $serviceId The service
     *
     * @return mixed
     */
    public function getSingleton(string $serviceId)
    {
        // If the service isn't a singleton but is provided
        if (! $this->isSingleton($serviceId) && $this->isProvided($serviceId)) {
            // Initialize the provided service
            $this->initializeProvided($serviceId);
        }

        return self::$singletons[$serviceId];
    }

    /**
     * Get a provided service from the container.
     *
     * @param string $serviceId The service
     * @param array  $arguments [optional] The arguments
     * @param string $context   [optional] The context class name || function name || variable name
     * @param string $member    [optional] The context member method name || property name
     *
     * @return mixed
     */
    public function getProvided(
        string $serviceId,
        array $arguments = null,
        string $context = null,
        string $member = null
    ) {
        $this->initializeProvided($serviceId);

        return $this->get($serviceId, $arguments, $context, $member);
    }

    /**
     * Get the context service id.
     *
     * @param string $serviceId The service
     * @param string $context   [optional] The context class name || function name || variable name
     * @param string $member    [optional] The context member method name || property name
     *
     * @return string
     */
    public function contextServiceId(string $serviceId, string $context = null, string $member = null): string
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
}
