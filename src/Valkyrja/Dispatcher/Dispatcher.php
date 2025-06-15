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

namespace Valkyrja\Dispatcher;

use Closure;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Contract\ContextAwareContainer;
use Valkyrja\Dispatcher\Contract\Dispatcher as Contract;
use Valkyrja\Dispatcher\Exception\InvalidArgumentException;
use Valkyrja\Dispatcher\Model\Contract\Dispatch;

use function array_map;
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
        protected Container $container = new \Valkyrja\Container\Container()
    ) {
    }

    /**
     * @inheritDoc
     */
    public function dispatch(Dispatch $dispatch, array|null $arguments = null): mixed
    {
        return match (true) {
            $dispatch->isMethod() => $this->dispatchClassMethod($dispatch, $arguments),
            $dispatch->isProperty() => $this->dispatchClassProperty($dispatch),
            $dispatch->isConstant() => $this->dispatchConstant($dispatch),
            $dispatch->isClass() => $this->dispatchClass($dispatch, $arguments),
            $dispatch->isFunction() => $this->dispatchFunction($dispatch, $arguments),
            $dispatch->isClosure() => $this->dispatchClosure($dispatch, $arguments),
            $dispatch->isVariable() => $this->dispatchVariable($dispatch),
            default => throw new InvalidArgumentException('Invalid dispatch'),
        };
    }

    /**
     * Dispatch a class method.
     *
     * @param Dispatch                     $dispatch  The dispatch
     * @param array<array-key, mixed>|null $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchClassMethod(Dispatch $dispatch, array|null $arguments = null): mixed
    {
        /** @var string $method */
        $method = $dispatch->getMethod();
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];
        /** @var class-string|object $class */
        $class = $this->getClassFromDispatch($dispatch);
        /**
         * @psalm-suppress MixedMethodCall The developer should have passed the proper arguments
         *
         * @var mixed $response
         */
        $response = is_string($class)
            ? $class::$method(...$arguments)
            : $class->$method(...$arguments);

        return $response;
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
        /** @var string $property */
        $property = $dispatch->getProperty();
        /** @var class-string|object $class */
        $class = $this->getClassFromDispatch($dispatch);
        /** @var mixed $response */
        $response = is_string($class)
            ? $class::$$property
            : $class->{$property};

        return $response;
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
        /** @var string $constant */
        $constant = $dispatch->getConstant();
        $constant = ($class = $dispatch->getClass())
            ? $class . '::' . $constant
            : $constant;

        return constant($constant);
    }

    /**
     * Dispatch a class.
     *
     * @param Dispatch                     $dispatch  The dispatch
     * @param array<array-key, mixed>|null $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchClass(Dispatch $dispatch, array|null $arguments = null): mixed
    {
        /** @var class-string $className */
        $className = $dispatch->getClass();
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];

        // Get the class through the container
        return $this->container->get($className, $arguments);
    }

    /**
     * Dispatch a function.
     *
     * @param Dispatch                     $dispatch  The dispatch
     * @param array<array-key, mixed>|null $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchFunction(Dispatch $dispatch, array|null $arguments = null): mixed
    {
        /** @var callable-string $function */
        $function = $dispatch->getFunction();
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];

        return $function(...$arguments);
    }

    /**
     * Dispatch a closure.
     *
     * @param Dispatch                     $dispatch  The dispatch
     * @param array<array-key, mixed>|null $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchClosure(Dispatch $dispatch, array|null $arguments = null): mixed
    {
        /** @var Closure $closure */
        $closure = $dispatch->getClosure();
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];

        return $closure(...$arguments);
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
        /** @var string $variable */
        $variable = $dispatch->getVariable();

        global $$variable;

        return $$variable;
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
    protected function getClassFromDispatch(Dispatch $dispatch): object|string
    {
        if (($class = $dispatch->getClass()) === null) {
            throw new InvalidArgumentException('Invalid class defined in dispatch model.');
        }

        if ($dispatch->isStatic()) {
            return $class;
        }

        /** @var object $classInstance */
        $classInstance = $this->container->get($class);

        return $classInstance;
    }

    /**
     * Get a dispatch's arguments.
     *
     * @param Dispatch                     $dispatch  The dispatch
     * @param array<array-key, mixed>|null $arguments [optional] The arguments
     *
     * @return array<array-key, mixed>|null
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
     * @return array<array-key, mixed>|null
     */
    protected function getDependencies(Dispatch $dispatch): array|null
    {
        // If there are dependencies
        if (($dependencies = $dispatch->getDependencies()) === null) {
            return [];
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

        return array_map(
            static fn (string $dependency): mixed => $containerContext !== null && $containerContext->has($dependency)
                ? $containerContext->get($dependency)
                : $container->get($dependency),
            $dependencies
        );
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
