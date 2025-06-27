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

use Valkyrja\Support\Config as AbstractConfig;

/**
 * Config class to use to test abstract config.
 *
 * @author Melech Mizrachi
 *
 * @property string $protected
 */
class ConfigClass extends AbstractConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        'public'   => 'DATA_CONFIG_PUBLIC',
        'nullable' => 'DATA_CONFIG_NULLABLE',
    ];

    public string $public = 'public';

    public string|null $nullable = null;
}
