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

namespace Valkyrja\Broadcast\Config;

use Valkyrja\Broadcast\Adapter\LogAdapter;
use Valkyrja\Broadcast\Constant\ConfigName;

/**
 * Class LogConfiguration.
 *
 * @author Melech Mizrachi
 */
class LogConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => 'BROADCAST_LOG_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'BROADCAST_LOG_DRIVER_CLASS',
        ConfigName::LOG_NAME      => 'BROADCAST_LOG_LOG_NAME',
    ];

    public function __construct(
        public string|null $logName = null
    ) {
        parent::__construct(
            adapterClass: LogAdapter::class,
        );
    }
}
