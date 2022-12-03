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

namespace Valkyrja\Dispatcher\Dispatchers;

use InvalidArgumentException;
use Valkyrja\Container\Container;
use Valkyrja\Dispatcher\Constants\Constant;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Dispatcher\Dispatcher as Contract;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Type\Exceptions\InvalidClassProvidedException;

use function is_callable;
use function is_string;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 */
class Dispatcher implements Contract
{
    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Dispatcher constructor.
     *
     * @param Container $container The container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function verifyDispatch(Dispatch $dispatch): void
    {
        $this->verifyNotEmptyDispatch($dispatch);
        $this->verifyClassMethod($dispatch);
        $this->verifyClassProperty($dispatch);
        $this->verifyFunction($dispatch);
    }

    /**
     * @inheritDoc
     */
    public function dispatch(Dispatch $dispatch, array $arguments = null): mixed
    {
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments);

        // Attempt to dispatch the dispatch
        $response = $this->dispatchClassMethod($dispatch, $arguments)
            ?? $this->dispatchClassProperty($dispatch)
            ?? $this->dispatchClass($dispatch, $arguments)
            ?? $this->dispatchFunction($dispatch, $arguments)
            ?? $this->dispatchClosure($dispatch, $arguments);
        // TODO: Add Constant and Variable ability

        // If the response was initially null and we added the dispatched text to avoid calling each subsequent
        // dispatcher thereafter so let's reset it to null
        return $response !== Constant::DISPATCHED ? $response : null;
    }

    /**
     * @inheritDoc
     */
    public function dispatchClassMethod(Dispatch $dispatch, array $arguments = null): mixed
    {
        // Ensure a class and method exist before continuing
        if (! $dispatch->isMethod()) {
            return null;
        }

        if (! $method = $dispatch->getMethod()) {
            throw new InvalidMethodException("Expecting a valid method: $method provided");
        }

        $class     = $this->getClassFromDispatch($dispatch);
        $arguments = $arguments ?? [];
        /** @var mixed $response */
        $response = is_string($class)
            ? $class::$method(...$arguments)
            : (/** @var object $class */
            $class->$method(...$arguments));

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * @inheritDoc
     */
    public function dispatchClassProperty(Dispatch $dispatch): mixed
    {
        // Ensure a class and property exist before continuing
        if (! $dispatch->isProperty()) {
            return null;
        }

        if (! $property = $dispatch->getProperty()) {
            throw new InvalidPropertyException("Expecting a valid property: $property provided");
        }

        $class = $this->getClassFromDispatch($dispatch);
        /** @var mixed $response */
        $response = is_string($class) ? $class::$$property : $class->{$property};

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * @inheritDoc
     */
    public function dispatchClass(Dispatch $dispatch, array $arguments = null): mixed
    {
        // Ensure a class exists before continuing
        if (! $dispatch->isClass()) {
            return null;
        }

        if (! $className = $dispatch->getClass()) {
            throw new InvalidClassProvidedException("Expecting a valid class: $className provided");
        }

        // If the class is the id then this item is not yet set in the
        // service container so it needs a new instance returned
        if ($className === $dispatch->getId()) {
            $arguments = $arguments ?? [];
            $class     = new $className(...$arguments);
        } else {
            // Get the class through the container
            $class = $this->container->get($className, $arguments ?? []);
        }

        return $class ?? Constant::DISPATCHED;
    }

    /**
     * @inheritDoc
     */
    public function dispatchFunction(Dispatch $dispatch, array $arguments = null): mixed
    {
        // Ensure a function exists before continuing
        if (! $dispatch->isFunction()) {
            return null;
        }

        if (! $function = $dispatch->getFunction()) {
            throw new InvalidFunctionException("Expecting a valid callable: $function provided");
        }

        $arguments = $arguments ?? [];
        $response  = $function(...$arguments);

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * @inheritDoc
     */
    public function dispatchClosure(Dispatch $dispatch, array $arguments = null): mixed
    {
        // Ensure a closure exists before continuing
        if (! $dispatch->isClosure()) {
            return null;
        }

        if (! $closure = $dispatch->getClosure()) {
            throw new InvalidClosureException("Expecting a valid closure: Null provided");
        }

        $arguments = $arguments ?? [];
        $response  = $closure(...$arguments);

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * @inheritDoc
     */
    public function verifyClassMethod(Dispatch $dispatch): void
    {
        if ($this->isInvalidClassMethod($dispatch)) {
            throw new InvalidMethodException(
                'Method does not exist in class : '
                . $dispatch->getName() . ' '
                . $dispatch->getClass()
                . '@'
                . $dispatch->getMethod()
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function verifyClassProperty(Dispatch $dispatch): void
    {
        if ($this->isInvalidClassProperty($dispatch)) {
            throw new InvalidPropertyException(
                'Property does not exist in class : '
                . $dispatch->getName() . ' '
                . $dispatch->getClass()
                . '@'
                . $dispatch->getProperty()
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function verifyFunction(Dispatch $dispatch): void
    {
        // If a function is set and is not callable
        if ($this->isInvalidFunction($dispatch)) {
            // Throw a new invalid function exception
            throw new InvalidFunctionException(
                'Function is not callable for : '
                . $dispatch->getName() . ' '
                . $dispatch->getFunction()
            );
        }
    }

    /**
     * Determine if a dispatch's class/method combination is invalid.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function isInvalidClassMethod(Dispatch $dispatch): bool
    {
        return $dispatch->isMethod()
            && ($class = $dispatch->getClass())
            && ($method = $dispatch->getMethod())
            && ! method_exists($class, $method);
    }

    /**
     * Determine if a dispatch's class/property combination is invalid.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function isInvalidClassProperty(Dispatch $dispatch): bool
    {
        return $dispatch->isProperty()
            && ($class = $dispatch->getClass())
            && ($property = $dispatch->getProperty())
            && ! property_exists($class, $property);
    }

    /**
     * Get class from dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidArgumentException
     *
     * @return object|class-string
     */
    protected function getClassFromDispatch(Dispatch $dispatch): mixed
    {
        if (! $class = $dispatch->getClass()) {
            throw new InvalidArgumentException('Invalid class defined in dispatch model.');
        }

        return $dispatch->isStatic() ? $class : $this->container->get($class);
    }

    /**
     * Determine if a dispatch's function is invalid.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function isInvalidFunction(Dispatch $dispatch): bool
    {
        return $dispatch->isFunction() && ! is_callable($dispatch->getFunction());
    }

    /**
     * Verify that a dispatch has something to dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidDispatchCapabilityException
     *
     * @return void
     */
    protected function verifyNotEmptyDispatch(Dispatch $dispatch): void
    {
        // If a function, closure, and class or method are not set
        if ($this->isEmptyDispatch($dispatch)) {
            // Throw a new invalid dispatch capability exception
            throw new InvalidDispatchCapabilityException(
                'Dispatch capability is not valid for : '
                . $dispatch->getName()
            );
        }
    }

    /**
     * Check if a dispatch is empty.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function isEmptyDispatch(Dispatch $dispatch): bool
    {
        return ! $dispatch->getFunction()
            && ! $dispatch->getClosure()
            && ! $dispatch->getClass()
            && ! $dispatch->getMethod()
            && ! $dispatch->getProperty();
    }

    /**
     * Get a dispatch's arguments.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments [optional] The arguments
     *
     * @return array|null
     */
    protected function getArguments(Dispatch $dispatch, array $arguments = null): ?array
    {
        // Get either the arguments passed or from the dispatch model
        $arguments = $arguments ?? $dispatch->getArguments();

        // Set the listener arguments to a new blank array
        $dependencies = $this->getDependencies($dispatch);

        // If there are no arguments
        if (null === $arguments) {
            // Return the dependencies only
            return $dependencies;
        }

        // Iterate through the arguments
        foreach ($arguments as $argument) {
            // Append the argument to the arguments list
            $dependencies[] = $this->getArgumentValue($argument);
        }

        return $dependencies;
    }

    /**
     * Get a dispatch's dependencies.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return array|null
     */
    protected function getDependencies(Dispatch $dispatch): ?array
    {
        $dependenciesInstances = [];

        // If there are dependencies
        if (($dependencies = $dispatch->getDependencies()) === null) {
            return $dependenciesInstances;
        }

        $context = $dispatch->getClass() ?? $dispatch->getFunction() ?? '';
        $member  = $dispatch->getMethod() ?? $dispatch->getProperty();

        $container        = $this->container;
        $containerContext = $container->withContext($context, $member);

        // Iterate through all the dependencies
        foreach ($dependencies as $dependency) {
            // If there is a context dependency
            if ($containerContext->has($dependency)) {
                // Set the context dependency from the container
                $dependenciesInstances[] = $containerContext->get($dependency);

                continue;
            }

            // Set the dependency from the container
            $dependenciesInstances[] = $container->get($dependency);
        }

        return $dependenciesInstances;
    }

    /**
     * Get argument value.
     *
     * @param mixed $argument The argument
     *
     * @return mixed
     */
    protected function getArgumentValue(mixed $argument): mixed
    {
        if ($argument instanceof Dispatch) {
            // Dispatch the argument and set the results to the argument
            $argument = $this->dispatch($argument);
        }

        return $argument;
    }
}
