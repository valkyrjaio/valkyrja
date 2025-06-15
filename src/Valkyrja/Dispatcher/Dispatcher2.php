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

use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Contract\ContextAwareContainer;
use Valkyrja\Dispatcher\Contract\Dispatcher2 as Contract;
use Valkyrja\Dispatcher\Data\CallableDispatch;
use Valkyrja\Dispatcher\Data\ClassDispatch;
use Valkyrja\Dispatcher\Data\ConstantDispatch;
use Valkyrja\Dispatcher\Data\Dispatch;
use Valkyrja\Dispatcher\Data\GlobalVariableDispatch;
use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Dispatcher\Data\PropertyDispatch;
use Valkyrja\Dispatcher\Exception\InvalidArgumentException;

use function array_map;
use function constant;
use function is_array;
use function is_string;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 */
class Dispatcher2 implements Contract
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
            $dispatch instanceof MethodDispatch => $this->dispatchClassMethod($dispatch, $arguments),
            $dispatch instanceof PropertyDispatch => $this->dispatchClassProperty($dispatch),
            $dispatch instanceof ConstantDispatch => $this->dispatchConstant($dispatch),
            $dispatch instanceof ClassDispatch => $this->dispatchClass($dispatch, $arguments),
            $dispatch instanceof CallableDispatch => $this->dispatchCallable($dispatch, $arguments),
            $dispatch instanceof GlobalVariableDispatch => $this->dispatchVariable($dispatch),
            default => throw new InvalidArgumentException('Invalid dispatch'),
        };
    }

    /**
     * Dispatch a class method.
     *
     * @param MethodDispatch               $dispatch  The dispatch
     * @param array<array-key, mixed>|null $arguments The arguments
     *
     * @return mixed
     */
    protected function dispatchClassMethod(MethodDispatch $dispatch, array|null $arguments = null): mixed
    {
        $method = $dispatch->getMethod();
        // Get the arguments with dependencies
        $arguments = $this->getArguments($dispatch, $arguments) ?? [];
        // Get the class
        $class = $dispatch->getClass();
        /**
         * @psalm-suppress MixedMethodCall The developer should have passed the proper arguments
         *
         * @var mixed $response
         */
        $response = $dispatch->isStatic()
            ? $class::$method(...$arguments)
            : $this->container->get($class)->$method(...$arguments);

        return $response;
    }

    /**
     * Dispatch a class property.
     *
     * @param PropertyDispatch $dispatch The dispatch
     *
     * @return mixed
     */
    protected function dispatchClassProperty(PropertyDispatch $dispatch): mixed
    {
        $property = $dispatch->getProperty();
        // Get the class
        $class = $dispatch->getClass();
        /** @var mixed $response */
        $response = $dispatch->isStatic()
            ? $class::$$property
            : $this->container->get($class)->{$property};

        return $response;
    }

    /**
     * Dispatch a constant.
     *
     * @param ConstantDispatch $dispatch The dispatch
     *
     * @return mixed
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
     * @param ClassDispatch                $dispatch  The dispatch
     * @param array<array-key, mixed>|null $arguments The arguments
     *
     * @return mixed
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
     * @param CallableDispatch             $dispatch  The dispatch
     * @param array<array-key, mixed>|null $arguments The arguments
     *
     * @return mixed
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
     *
     * @return mixed
     */
    protected function dispatchVariable(GlobalVariableDispatch $dispatch): mixed
    {
        $variable = $dispatch->getVariable();

        global $$variable;

        return $$variable;
    }

    /**
     * Get a dispatch's arguments.
     *
     * @param CallableDispatch|ClassDispatch|MethodDispatch $dispatch  The dispatch
     * @param array<array-key, mixed>|null                  $arguments [optional] The arguments
     *
     * @return array<array-key, mixed>|null
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
        foreach ($arguments as $argument) {
            // Append the argument to the arguments list
            $dependencies[] = $this->getArgumentValue($argument);
        }

        return $dependencies;
    }

    /**
     * Get a dispatch's dependencies.
     *
     * @param CallableDispatch|ClassDispatch|MethodDispatch $dispatch The dispatch
     *
     * @return array<array-key, mixed>|null
     */
    protected function getDependencies(CallableDispatch|ClassDispatch|MethodDispatch $dispatch): array|null
    {
        // If there are dependencies
        if (($dependencies = $dispatch->getDependencies()) === null) {
            return [];
        }

        $context = match (true) {
            $dispatch instanceof ClassDispatch => $dispatch->getClass(),
            $dispatch instanceof CallableDispatch => is_array($callable = $dispatch->getCallable()) && is_string($callable[0])
                ? $callable[0]
                : null,
        };
        $member  = match (true) {
            $dispatch instanceof MethodDispatch => $dispatch->getMethod(),
            $dispatch instanceof CallableDispatch => is_array($callable = $dispatch->getCallable()) && is_string($callable[1])
                ? $callable[1]
                : null,
            default => null
        };

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
