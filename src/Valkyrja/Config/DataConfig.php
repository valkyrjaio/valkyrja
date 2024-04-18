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
use RuntimeException;
use Valkyrja\Application\Env;

use function defined;

/**
 * Abstract Class Config.
 *
 * @author Melech Mizrachi
 *
 * @implements ArrayAccess<string, mixed>
 */
abstract class DataConfig implements ArrayAccess
{
    /**
     * The model properties env keys.
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [];

    /**
     * Create config from Env.
     *
     * @param class-string<Env> $env The env
     */
    public static function fromEnv(string $env): static
    {
        $new = new static();

        $new->setPropertiesBeforeSettingFromEnv();
        $new->setPropertiesFromEnv($env);
        $new->setPropertiesAfterSettingFromEnv();

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        /** @var string $offset */
        return isset($this->$offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        /** @var string $offset */
        return $this->$offset;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        /** @var string $offset */
        $this->$offset = $value;
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
     * @param class-string<Env> $env The env
     */
    protected function setPropertiesFromEnv(string $env): void
    {
        foreach (static::$envKeys as $property => $value) {
            if (defined("$env::$value")) {
                $this->$property = constant("$env::$value") ?? $this->$property;
            }
        }
    }

    /**
     * Set properties' values before setting from env.
     */
    protected function setPropertiesBeforeSettingFromEnv(): void
    {
    }

    /**
     * Set properties' values after setting from env.
     */
    protected function setPropertiesAfterSettingFromEnv(): void
    {
    }
}
