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
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Dispatcher\Enums\Constant;
use Valkyrja\Dispatcher\Exceptions\InvalidMethodException;
use Valkyrja\Dispatcher\Exceptions\InvalidPropertyException;

use function method_exists;
use function property_exists;

/**
 * Trait ClassDispatcher.
 *
 * @author Melech Mizrachi
 */
trait ClassDispatcher
{
    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Verify the class and method of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidMethodException
     *
     * @return void
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
     * Verify the class and property of a dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidPropertyException
     *
     * @return void
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
     * Dispatch a class method.
     *
     * @param Dispatch $dispatch  The dispatch
     * @param array    $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function dispatchClassMethod(Dispatch $dispatch, array $arguments = null)
    {
        // Ensure a class and method exist before continuing
        if (! $this->hasValidClassMethod($dispatch)) {
            return null;
        }

        $class     = $this->getClassFromDispatch($dispatch);
        $method    = $dispatch->getMethod();
        $arguments = $arguments ?? [];
        $response  = $dispatch->isStatic() ? $class::$method(...$arguments) : $class->$method(...$arguments);

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * Dispatch a class property.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return mixed
     */
    public function dispatchClassProperty(Dispatch $dispatch)
    {
        // Ensure a class and property exist before continuing
        if (! $this->hasValidClassProperty($dispatch)) {
            return null;
        }

        $class    = $this->getClassFromDispatch($dispatch);
        $property = $dispatch->getProperty();
        $response = $dispatch->isStatic() ? $class::$$property : $class->{$property};

        return $response ?? Constant::DISPATCHED;
    }

    /**
     * Dispatch a class.
     *
     * @param Dispatch $dispatch  The dispatch
     * @param array    $arguments [optional] The arguments
     *
     * @return mixed
     */
    public function dispatchClass(Dispatch $dispatch, array $arguments = null)
    {
        // Ensure a class exists before continuing
        if (! $this->hasValidClass($dispatch)) {
            return null;
        }

        // If the class is the id then this item is not yet set in the
        // service container so it needs a new instance returned
        if ($dispatch->getClass() === $dispatch->getId()) {
            // Get the class from the dispatcher
            $class     = $dispatch->getClass();
            $arguments = $arguments ?? [];
            $class     = new $class(...$arguments);
        } else {
            // Get the class through the container
            $class = $this->container->get($dispatch->getClass(), $arguments ?? []);
        }

        return $class ?? Constant::DISPATCHED;
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
        return $this->hasValidClassMethod($dispatch) && ! method_exists($dispatch->getClass(), $dispatch->getMethod());
    }

    /**
     * Determine if a dispatch has a class/method set.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function hasValidClassMethod(Dispatch $dispatch): bool
    {
        return $this->hasValidClass($dispatch) && null !== $dispatch->getMethod();
    }

    /**
     * Determine if a dispatch has a class set.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function hasValidClass(Dispatch $dispatch): bool
    {
        return null !== $dispatch->getClass();
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
        return $this->hasValidClassProperty($dispatch)
            && ! property_exists($dispatch->getClass(), $dispatch->getProperty());
    }

    /**
     * Determine if a dispatch has a class/property set.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @return bool
     */
    protected function hasValidClassProperty(Dispatch $dispatch): bool
    {
        return $this->hasValidClass($dispatch) && null !== $dispatch->getProperty();
    }

    /**
     * Get class from dispatch.
     *
     * @param Dispatch $dispatch The dispatch
     *
     * @throws InvalidArgumentException
     *
     * @return mixed|string|null
     */
    protected function getClassFromDispatch(Dispatch $dispatch)
    {
        if (! $dispatch->getClass()) {
            throw new InvalidArgumentException('Invalid class defined in dispatch model.');
        }

        return $dispatch->isStatic() ? $dispatch->getClass() : $this->container->get($dispatch->getClass());
    }
}
