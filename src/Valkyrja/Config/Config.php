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

use function constant;
use function defined;
use function is_callable;

/**
 * Abstract Class Config.
 *
 * @author Melech Mizrachi
 */
abstract class Config
{
    /**
     * The model properties env keys.
     *
     * @var array<string, string>
     */
    protected static array $envNames = [];

    /**
     * Create config from Env.
     *
     * @param class-string $env The env
     */
    public static function fromEnv(string $env): static
    {
        $new = new static();

        $new->setPropertiesBeforeSettingFromEnv($env);
        $new->setPropertiesFromEnv($env);
        $new->setPropertiesAfterSettingFromEnv($env);

        return $new;
    }

    /**
     * Set properties from env.
     *
     * @param class-string $env The env
     */
    protected function setPropertiesFromEnv(string $env): void
    {
        foreach (static::$envNames as $propertyName => $envName) {
            if (defined("$env::$envName")) {
                $constantValue = constant("$env::$envName");

                if (is_callable($constantValue)) {
                    $this->$propertyName = $constantValue()
                        ?? $this->$propertyName;

                    continue;
                }

                $this->$propertyName = $constantValue
                    ?? $this->$propertyName;
            }
        }
    }

    /**
     * Set properties' values before setting from env.
     *
     * @param class-string $env The env
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
    }

    /**
     * Set properties' values after setting from env.
     *
     * @param class-string $env The env
     */
    protected function setPropertiesAfterSettingFromEnv(string $env): void
    {
    }
}
