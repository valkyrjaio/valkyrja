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

use Valkyrja\Exception\RuntimeException;
use Valkyrja\Type\Model\Model;

use function Valkyrja\env;

/**
 * Abstract Class Config.
 *
 * @author Melech Mizrachi
 */
abstract class Config extends Model
{
    /**
     * @inheritDoc
     */
    protected static bool $shouldSetOriginalProperties = false;

    /**
     * The model properties env keys.
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [];

    /**
     * Model constructor.
     *
     * @param array<string, mixed>|null $properties [optional] The properties to set
     * @param bool                      $setup      [optional] Whether to setup this config
     */
    public function __construct(?array $properties = null, bool $setup = false)
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
     * Setup the config.
     *
     * @param array<string, mixed>|null $properties [optional] The properties to set
     *
     * @return void
     */
    protected function setup(?array $properties = null): void
    {
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        /** @var string $offset */
        return isset($this->{$offset});
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        /** @var string $offset */
        return $this->__get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        /** @var string $offset */
        $this->__set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new RuntimeException("Cannot remove offset with name $offset from config.");
    }

    /**
     * Set properties from env.
     *
     * @return void
     */
    protected function setPropertiesFromEnv(): void
    {
        foreach (static::$envKeys as $property => $value) {
            $this->__set($property, env($value) ?? $this->__get($property));
        }
    }
}
