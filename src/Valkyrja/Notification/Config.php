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

namespace Valkyrja\Notification;

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Notification\Constant\ConfigName;
use Valkyrja\Notification\Constant\EnvName;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::NOTIFICATIONS => EnvName::NOTIFICATIONS,
    ];

    /**
     * @param array<string, mixed> $notifications The notifications
     */
    public function __construct(
        public array $notifications = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    protected function setPropertiesBeforeSettingFromEnv(string $env): void
    {
    }
}
