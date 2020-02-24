<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Facade\Facades;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Container\Container;
use Valkyrja\Facade\Facade as FacadeContract;

use function in_array;
use function is_object;
use function is_string;

/**
 * Abstract Class Facade.
 *
 * @author Melech Mizrachi
 */
abstract class Facade implements FacadeContract
{
    /**
     * The instance.
     *
     * @var object
     */
    protected static ?object $instance = null;

    /**
     * The container.
     *
     * @var Container
     */
    private static ?Container $container = null;

    /**
     * Get the instance.
     *
     * @return object
     */
    public static function getInstance(): object
    {
        if (null === static::$instance) {
            static::setInstance(static::instance());
        }

        return self::$instance;
    }

    /**
     * Set the instance.
     *
     * @param string|object $instance
     *
     * @return void
     */
    public static function setInstance($instance): void
    {
        if (is_object($instance)) {
            static::$instance = $instance;

            return;
        }

        if (! is_string($instance)) {
            throw new InvalidArgumentException('Instance must be a string or an object.');
        }

        static::$instance = self::getContainer()->get($instance);
    }

    /**
     * Handle dynamic, static calls to the instance.
     *
     * @param string $method The method to call
     * @param array  $args   The argument
     *
     * @throws RuntimeException
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $args = [])
    {
        $instance = static::getInstance();

        if (! is_object($instance)) {
            throw new RuntimeException('A facade instance has not been set.');
        }

        if (static::isStaticMethod($method)) {
            return $instance::$method(...$args);
        }

        return $instance->$method(...$args);
    }

    /**
     * Get the container.
     *
     * @return Container
     */
    protected static function getContainer(): Container
    {
        return self::$container ?? (self::$container = container());
    }

    /**
     * Get an array of static methods.
     *
     * @return array
     */
    protected static function getStaticMethods(): array
    {
        return [];
    }

    /**
     * Determine if a method is static.
     *
     * @param string $method The method
     *
     * @return bool
     */
    protected static function isStaticMethod(string $method): bool
    {
        $staticMethods = static::getStaticMethods();

        return ! empty($staticMethods) && (isset($staticMethods[$method]) || in_array($method, $staticMethods, true));
    }

    /**
     * The facade instance.
     *
     * @return string|object
     */
    abstract protected static function instance();
}
