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

namespace Valkyrja\Path;

use Valkyrja\Config\DataConfig as ParentConfig;
use Valkyrja\Path\Constant\ConfigName;
use Valkyrja\Path\Constant\ConfigValue;
use Valkyrja\Path\Constant\EnvName;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class DataConfig extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::PATTERNS => EnvName::PATTERNS,
    ];

    /**
     * @param array<string, string> $patterns
     */
    public function __construct(
        public array $patterns = ConfigValue::PATTERNS,
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
    }
}
