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

namespace Valkyrja\Facade;

use RuntimeException;
use Valkyrja\Facade\Exception\InvalidArgumentException;

use function in_array;
use function is_object;

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
     * @param object $instance The instance
     *
     * @return void
     */
    public static function setInstance(object $instance): void
    {
        self::$instances[static::class] = $instance;
    }

    /**
     * Handle dynamic, static calls to the instance.
     *
     * @param string                  $method The method to call
     * @param array<array-key, mixed> $args   [optional] The argument
     *
     * @throws RuntimeException
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $args = [])
    {
        /** @var mixed $instance */
        $instance = static::getInstance();

        if (! is_object($instance)) {
            throw new InvalidArgumentException('A facade instance has not been set.');
        }

        /** @var object $instance */
        if (static::isStaticMethod($method)) {
            return $instance::$method(...$args);
        }

        return $instance->$method(...$args);
    }

    /**
     * Get an array of static methods.
     *
     * @return string[]
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

        return $staticMethods !== [] && (isset($staticMethods[$method]) || in_array($method, $staticMethods, true));
    }

    /**
     * The facade instance.
     *
     * @return object
     */
    abstract protected static function instance(): object;
}
