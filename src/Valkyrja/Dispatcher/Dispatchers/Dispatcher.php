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
use Valkyrja\Application\Application;
use Valkyrja\Container\Service;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Dispatcher\Dispatcher as DispatcherContract;
use Valkyrja\Dispatcher\Enums\Constant;
use Valkyrja\Dispatcher\Exceptions\InvalidDispatchCapabilityException;
use Valkyrja\Dispatcher\Exceptions\InvalidFunctionException;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;

/**
 * Class Dispatcher.
 *
 * @author Melech Mizrachi
 */
class Dispatcher implements DispatcherContract
{
    use CallableDispatcher;
    use ClassDispatcher;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * Dispatcher constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app       = $application;
        $this->container = $application->container();
    }

    /**
     * Verify the dispatch's dispatch capabilities.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidDispatchCapabilityException
     * @throws InvalidFunctionException
     * @throws InvalidMethodException
     * @throws InvalidPropertyException
     *
     * @return void
     */
    public function verifyDispatch(Dispatch $dispatch): void
    {
        $this->verifyNotEmptyDispatch($dispatch);
        $this->verifyClassMethod($dispatch);
        $this->verifyClassProperty($dispatch);
        $this->verifyFunction($dispatch);
    }

    /**
     * Dispatch a callable.
     *
     * @param Dispatch $dispatch  The dispatch
     * @param array    $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function dispatch(Dispatch $dispatch, array $arguments = null)
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
        return null === $dispatch->getFunction()
            && null === $dispatch->getClosure()
            && null === $dispatch->getClass()
            && null === $dispatch->getMethod()
            && null === $dispatch->getProperty();
    }

    /**
     * Get a dispatch's arguments.
     *
     * @param Dispatch $dispatch  The dispatch
     * @param array    $arguments [optional] The arguments
     *
     * @return array
     */
    protected function getArguments(Dispatch $dispatch, array $arguments = null): ?array
    {
        // Get either the arguments passed or from the dispatch model
        $arguments = $arguments ?? $dispatch->getArguments();
        $context   = $dispatch->getClass() ?? $dispatch->getFunction();
        $member    = $dispatch->getProperty() ?? $dispatch->getMethod();

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
            $dependencies[] = $this->getArgumentValue($argument, $context, $member);
        }

        return $dependencies;
    }

    /**
     * Get a dispatch's dependencies.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return array
     */
    protected function getDependencies(Dispatch $dispatch): ?array
    {
        $dependencies = [];
        $context      = $dispatch->getClass() ?? $dispatch->getFunction();
        $member       = $dispatch->getMethod();

        // If there are dependencies
        if ($dispatch->getDependencies()) {
            // Iterate through all the dependencies
            foreach ($dispatch->getDependencies() as $dependency) {
                // Set the dependency in the list
                $dependencies[] = $this->container->get(
                    $dependency,
                    null,
                    $context,
                    $member
                );
            }
        }

        return $dependencies;
    }

    /**
     * Get argument value.
     *
     * @param mixed       $argument The argument
     * @param string|null $context  [optional] The context
     * @param string|null $member   [optional] The context member
     *
     * @return mixed
     */
    protected function getArgumentValue($argument, string $context = null, string $member = null)
    {
        // If the argument is a service
        if ($argument instanceof Service) {
            if (null === $argument->getId()) {
                throw new InvalidArgumentException('Invalid argument.');
            }

            // Dispatch the argument and set the results to the argument
            $argument = $this->container->get(
                $argument->getId(),
                null,
                $context,
                $member
            );
        } // If the argument is a dispatch
        elseif ($argument instanceof Dispatch) {
            // Dispatch the argument and set the results to the argument
            $argument = $this->dispatch($argument);
        }

        return $argument;
    }
}
