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

namespace Valkyrja\Support\Facade;

use InvalidArgumentException;
use RuntimeException;
use Valkyrja\Container\Container;

use function in_array;
use function is_object;
use function is_string;

/**
 * Abstract Class Facade.
 *
 * @author Melech Mizrachi
 */
abstract class Facade
{
    /**
     * The instance.
     *
     * @var object[]
     */
    protected static array $instances = [];

    /**
     * The container.
     *
     * @var Container
     */
    protected static Container $container;

    /**
     * Get the container.
     *
     * @return Container
     */
    public static function getContainer(): Container
    {
        return self::$container;
    }

    /**
     * Set the container.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }

    /**
     * Get the instance.
     *
     * @return object
     */
    public static function getInstance(): object
    {
        if (! isset(self::$instances[static::class])) {
            static::setInstance(static::instance());
        }

        return self::$instances[static::class];
    }

    /**
     * Set the instance.
     *
     * @param string|object $instance The instance
     *
     * @return void
     */
    public static function setInstance($instance): void
    {
        static::verifyInstance($instance);

        if (is_object($instance)) {
            self::$instances[static::class] = $instance;

            return;
        }

        self::$instances[static::class] = self::getContainer()->get($instance);
    }

    /**
     * Handle dynamic, static calls to the instance.
     *
     * @param string $method The method to call
     * @param array  $args   [optional] The argument
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
     * Verify an instance's type.
     *
     * @param mixed $instance The instance
     *
     * @return void
     */
    protected static function verifyInstance(mixed $instance): void
    {
        if (! is_string($instance) && ! is_object($instance)) {
            throw new InvalidArgumentException('Instance must be a string or an object.');
        }
    }

    /**
     * The facade instance.
     *
     * @return string|object
     */
    abstract protected static function instance();
}
