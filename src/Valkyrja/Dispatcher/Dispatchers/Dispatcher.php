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
use Valkyrja\Container\ContextAwareContainer;
use Valkyrja\Dispatcher\Constants\Constant;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Dispatcher\Dispatcher as Contract;
use Valkyrja\Dispatcher\Exceptions\InvalidClosureException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;
use Valkyrja\Type\Exceptions\InvalidClassProvidedException;

use function constant;
use function is_string;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 */
class Dispatcher implements Contract
{
    /**
     * Dispatcher constructor.
     *
     * @param Container $container The container
     */
    public function __construct(
        protected Container $container
    ) {
    }

    /**
     * @inheritDoc
     */
    public function dispatch(Dispatch $dispatch, array|null $arguments = null): mixed
    {
        // Attempt to dispatch the dispatch
        $response = $this->dispatchClassMethod($dispatch, $arguments)
            ?? $this->dispatchClassProperty($dispatch)
            ?? $this->dispatchConstant($dispatch)
            ?? $this->dispatchClass($dispatch, $arguments)
            ?? $this->dispatchFunction($dispatch, $arguments)
            ?? $this->dispatchClosure($dispatch, $arguments)
            ?? $this->dispatchVariable($dispatch);

        // If the response was initially null and we added the dispatched text to avoid calling each subsequent
        // dispatcher thereafter so let's reset it to null
        return $response !== Constant::DISPATCHED ? $response : null;
    }

    /**
     * Dispatch a class method.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchClassMethod(Dispatch $dispatch, array|null $arguments = null): mixed
    {
        // Ensure a class and method exist before continuing
        if (! $dispatch->isMethod()) {
            return null;
        }

        if (($method = $dispatch->getMethod()) === null) {
            throw new InvalidMethodException("Expecting a valid method: $method provided");
        }

        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];
        $class     = $this->getClassFromDispatch($dispatch);
        /** @var mixed $response */
        $response = is_string($class)
            ? $class::$method(...$arguments)
            : (/** @var object $class */
                $class->$method(...$arguments)
            );

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * Dispatch a class property.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return mixed
     */
    protected function dispatchClassProperty(Dispatch $dispatch): mixed
    {
        // Ensure a class and property exist before continuing
        if (! $dispatch->isProperty()) {
            return null;
        }

        if (($property = $dispatch->getProperty()) === null) {
            throw new InvalidPropertyException("Expecting a valid property: $property provided");
        }

        $class = $this->getClassFromDispatch($dispatch);
        /** @var mixed $response */
        $response = is_string($class)
            ? $class::$$property
            : $class->{$property};

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * Dispatch a constant.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return mixed
     */
    protected function dispatchConstant(Dispatch $dispatch): mixed
    {
        // Ensure a constant exists before continuing
        if (! $dispatch->isConstant()) {
            return null;
        }

        if (($constant = $dispatch->getConstant()) === null) {
            throw new InvalidClosureException('Expecting a valid constant: Null provided');
        }

        $constant = ($class = $dispatch->getClass())
            ? $class . '::' . $constant
            : $constant;
        $response = constant($constant);

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * Dispatch a class.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchClass(Dispatch $dispatch, array|null $arguments = null): mixed
    {
        // Ensure a class exists before continuing
        if (! $dispatch->isClass()) {
            return null;
        }

        if (($className = $dispatch->getClass()) === null) {
            throw new InvalidClassProvidedException("Expecting a valid class: $className provided");
        }

        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];

        // If the class is the id then this item is not yet set in the
        // service container so it needs a new instance returned
        if ($className === $dispatch->getId()) {
            $class = new $className(...$arguments);
        } else {
            // Get the class through the container
            $class = $this->container->get($className, $arguments);
        }

        return $class ?? Constant::DISPATCHED;
    }

    /**
     * Dispatch a function.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchFunction(Dispatch $dispatch, array|null $arguments = null): mixed
    {
        // Ensure a function exists before continuing
        if (! $dispatch->isFunction()) {
            return null;
        }

        if (($function = $dispatch->getFunction()) === null) {
            throw new InvalidFunctionException("Expecting a valid callable: $function provided");
        }

        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];
        $response  = $function(...$arguments);

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * Dispatch a closure.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchClosure(Dispatch $dispatch, array|null $arguments = null): mixed
    {
        // Ensure a closure exists before continuing
        if (! $dispatch->isClosure()) {
            return null;
        }

        if (($closure = $dispatch->getClosure()) === null) {
            throw new InvalidClosureException('Expecting a valid closure: Null provided');
        }

        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];
        $response  = $closure(...$arguments);

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * Dispatch a variable.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return mixed
     */
    protected function dispatchVariable(Dispatch $dispatch): mixed
    {
        // Ensure a variable exists before continuing
        if (! $dispatch->isVariable()) {
            return null;
        }

        if (($variable = $dispatch->getVariable()) === null) {
            throw new InvalidClosureException('Expecting a valid variable: Null provided');
        }

        global $$variable;

        $response = $$variable;

        return $response ?? Constant::DISPATCHED;
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
        if (($class = $dispatch->getClass()) === null) {
            throw new InvalidArgumentException('Invalid class defined in dispatch model.');
        }

        return $dispatch->isStatic() ? $class : $this->container->get($class);
    }

    /**
     * Get a dispatch's arguments.
     *
     * @param Dispatch   $dispatch  The dispatch
     * @param array|null $arguments [optional] The arguments
     *
     * @return array|null
     */
    protected function getArguments(Dispatch $dispatch, array|null $arguments = null): array|null
    {
        // Get either the arguments passed or from the dispatch model
        $arguments ??= $dispatch->getArguments();

        // Set the listener arguments to a new blank array
        $dependencies = $this->getDependencies($dispatch);

        // If there are no arguments
        if ($arguments === null) {
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
    protected function getDependencies(Dispatch $dispatch): array|null
    {
        $dependenciesInstances = [];

        // If there are dependencies
        if (($dependencies = $dispatch->getDependencies()) === null) {
            return $dependenciesInstances;
        }

        $context = $dispatch->getClass() ?? $dispatch->getFunction() ?? null;
        $member  = $dispatch->getMethod() ?? $dispatch->getProperty();

        $containerContext = null;

        $container  = $this->container;
        $hasContext = $context !== null && $container instanceof ContextAwareContainer;

        if ($hasContext) {
            /** @var ContextAwareContainer $container */
            $containerContext = $container->withContext($context, $member);
        }

        // Iterate through all the dependencies
        foreach ($dependencies as $dependency) {
            // Set the dependency from the container
            $dependenciesInstances[] = $hasContext && $containerContext?->has($dependency)
                ? $containerContext->get($dependency)
                : $container->get($dependency);
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
