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
use Valkyrja\Model\Models\Model;

use function Valkyrja\env;

/**
 * Abstract Class Config.
 *
 * @author Melech Mizrachi
 *
 * @implements ArrayAccess<string, mixed>
 */
abstract class Config extends Model implements ArrayAccess
{
    /**
     * @inheritDoc
     */
    protected static bool $shouldSetOriginalProperties = false;

    /**
     * The model properties env keys.
     *
     * @var string[]
     */
    protected static array $envKeys = [];

    /**
     * Model constructor.
     *
     * @param array|null $properties [optional] The properties to set
     * @param bool       $setup      [optional] Whether to setup this config
     */
    public function __construct(array $properties = null, bool $setup = false)
    {
        if ($setup) {
            $this->setup($properties);
        }

        if ($properties !== null) {
            $this->updateProperties($properties);
        }

        if ($setup) {
            $this->setPropertiesFromEnv();
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return isset($this->{$offset});
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        return $this->__get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->__set($offset, $value);
    }

    /**
     * @inheritDoc
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
     * Setup the config.
     *
     * @param array|null $properties [optional] The properties to set
     *
     * @return void
     */
    protected function setup(array $properties = null): void
    {
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
