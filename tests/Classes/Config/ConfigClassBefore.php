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

namespace Valkyrja\Tests\Classes\Config;

/**
 * Config class to use to test abstract config.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class ConfigClassBefore extends ConfigClass
{
    public const string PUBLIC   = 'publicBeforeEnv';
    public const string NULLABLE = 'nullableBeforeEnv';

    public function setPropertiesFromEnv(string $env): void
    {
        $this->public   = self::PUBLIC;
        $this->nullable = self::NULLABLE;

        parent::setPropertiesFromEnv($env);
    }
}
