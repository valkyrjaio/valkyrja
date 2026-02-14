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

namespace Valkyrja\Dispatch\Dispatcher;

use Override;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Dispatch\Data\CallableDispatch;
use Valkyrja\Dispatch\Data\ClassDispatch;
use Valkyrja\Dispatch\Data\ConstantDispatch;
use Valkyrja\Dispatch\Data\Contract\DispatchContract;
use Valkyrja\Dispatch\Data\GlobalVariableDispatch;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Dispatch\Data\PropertyDispatch;
use Valkyrja\Dispatch\Dispatcher\Contract\DispatcherContract;
use Valkyrja\Dispatch\Throwable\Exception\InvalidArgumentException;

use function array_map;
use function constant;

class Dispatcher implements DispatcherContract
{
    public function __construct(
        protected ContainerContract $container = new Container()
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function dispatch(DispatchContract $dispatch, array|null $arguments = null): mixed
    {
        return match (true) {
            $dispatch instanceof MethodDispatch         => $this->dispatchClassMethod($dispatch, $arguments),
            $dispatch instanceof PropertyDispatch       => $this->dispatchClassProperty($dispatch),
            $dispatch instanceof ConstantDispatch       => $this->dispatchConstant($dispatch),
            $dispatch instanceof ClassDispatch          => $this->dispatchClass($dispatch, $arguments),
            $dispatch instanceof CallableDispatch       => $this->dispatchCallable($dispatch, $arguments),
            $dispatch instanceof GlobalVariableDispatch => $this->dispatchVariable($dispatch),
            default                                     => throw new InvalidArgumentException('Invalid dispatch'),
        };
    }

    /**
     * Dispatch a class method.
     *
     * @param array<non-empty-string, mixed>|null $arguments The arguments
     */
    protected function dispatchClassMethod(MethodDispatch $dispatch, array|null $arguments = null): mixed
    {
        $method = $dispatch->getMethod();
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];
        // Get the class name
        $className = $dispatch->getClass();
        // Get the class
        $class = $dispatch->isStatic()
            ? $className
            : $this->container->get($className);

        // Do not try to determine if the method exists or not here because the method may be called via __call
        // Allow PHP to throw an error if the method does not exist and it is tried to be called here

        /** @var callable $callable */
        $callable = [$class, $method];

        /** @var scalar|object|array<array-key, mixed>|resource|null */
        return $callable(...$arguments);
    }

    /**
     * Dispatch a class property.
     *
     * @param PropertyDispatch $dispatch The dispatch
     */
    protected function dispatchClassProperty(PropertyDispatch $dispatch): mixed
    {
        $property = $dispatch->getProperty();
        // Get the class
        $class = $dispatch->getClass();

        // No null coalescing operator because we want to throw an error if the property does not exist

        if ($dispatch->isStatic()) {
            /** @var scalar|object|array<array-key, mixed>|resource|null */
            return $class::${$property};
        }

        /** @var scalar|object|array<array-key, mixed>|resource|null */
        return $this->container->get($class)->{$property};
    }

    /**
     * Dispatch a constant.
     *
     * @param ConstantDispatch $dispatch The dispatch
     */
    protected function dispatchConstant(ConstantDispatch $dispatch): mixed
    {
        $constant = $dispatch->getConstant();
        $constant = ($class = $dispatch->getClass())
            ? $class . '::' . $constant
            : $constant;

        return constant($constant);
    }

    /**
     * Dispatch a class.
     *
     * @param ClassDispatch                       $dispatch  The dispatch
     * @param array<non-empty-string, mixed>|null $arguments The arguments
     */
    protected function dispatchClass(ClassDispatch $dispatch, array|null $arguments = null): mixed
    {
        $className = $dispatch->getClass();
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];

        // Get the class through the container
        return $this->container->get($className, $arguments);
    }

    /**
     * Dispatch a function.
     *
     * @param CallableDispatch                    $dispatch  The dispatch
     * @param array<non-empty-string, mixed>|null $arguments The arguments
     */
    protected function dispatchCallable(CallableDispatch $dispatch, array|null $arguments = null): mixed
    {
        $callable = $dispatch->getCallable();
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];

        return $callable(...$arguments);
    }

    /**
     * Dispatch a variable.
     *
     * @param GlobalVariableDispatch $dispatch The dispatch
     */
    protected function dispatchVariable(GlobalVariableDispatch $dispatch): mixed
    {
        $variable = $dispatch->getVariable();

        global ${$variable};

        return ${$variable};
    }

    /**
     * Get a dispatch's arguments.
     *
     * @param CallableDispatch|ClassDispatch|MethodDispatch $dispatch  The dispatch
     * @param array<non-empty-string, mixed>|null           $arguments [optional] The arguments
     *
     * @return array<non-empty-string, mixed>|null
     */
    protected function getArguments(CallableDispatch|ClassDispatch|MethodDispatch $dispatch, array|null $arguments = null): array|null
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
        /** @var scalar|object|array<array-key, mixed>|resource|null $argument */
        foreach ($arguments as $key => $argument) {
            // Append the argument to the arguments list
            $dependencies[$key] = $this->getArgumentValue($argument);
        }

        return $dependencies;
    }

    /**
     * Get a dispatch's dependencies.
     *
     * @param CallableDispatch|ClassDispatch|MethodDispatch $dispatch The dispatch
     *
     * @return array<non-empty-string, mixed>
     */
    protected function getDependencies(CallableDispatch|ClassDispatch|MethodDispatch $dispatch): array
    {
        // If there are dependencies
        if (($dependencies = $dispatch->getDependencies()) === null) {
            return [];
        }

        $container = $this->container;

        return array_map(
            /** @param class-string $dependency */
            static fn (string $dependency): mixed => $container->get($dependency),
            $dependencies
        );
    }

    /**
     * Get argument value.
     *
     * @param mixed $argument The argument
     *
     * @return scalar|object|array<array-key, mixed>|resource|null
     */
    protected function getArgumentValue(mixed $argument): mixed
    {
        if ($argument instanceof DispatchContract) {
            // Dispatch the argument and set the results to the argument
            /** @var scalar|object|array<array-key, mixed>|resource|null */
            return $this->dispatch($argument);
        }

        /** @var scalar|object|array<array-key, mixed>|resource|null */

        return $argument;
    }
}
