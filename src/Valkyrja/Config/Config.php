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

namespace Valkyrja\Config;

use ArrayAccess;
use JsonException;
use Valkyrja\Support\Model\Classes\Model;

use function Valkyrja\env;

/**
 * Abstract Class Config.
 *
 * @author Melech Mizrachi
 */
abstract class Config extends Model implements ArrayAccess
{
    /**
     * The model properties env keys.
     *
     * @var string[]
     */
    protected static array $envKeys = [];

    /**
     * Model constructor.
     *
     * @param array|null $properties   [optional]
     * @param bool       $setupFromEnv [optional]
     *
     * @throws JsonException
     */
    public function __construct(array $properties = null, bool $setupFromEnv = false)
    {
        if (null !== $properties) {
            $this->__setProperties($properties);
        }

        if ($setupFromEnv) {
            $this->setPropertiesFromEnv();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->{$offset});
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->__set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->{$offset});
    }

    /**
     * Get the env keys.
     *
     * @return string[]
     */
    protected function getEnvKeys(): array
    {
        return static::$envKeys;
    }

    /**
     * Set properties from env.
     *
     * @return void
     */
    protected function setPropertiesFromEnv(): void
    {
        foreach (static::$envKeys as $property => $value) {
            $this->__set($property, env(static::$envKeys[$property]) ?? $this->__get($property));
        }
    }
}
