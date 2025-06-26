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

namespace Valkyrja\Http\Client\Config;

use Valkyrja\Http\Client\Adapter\LogAdapter;
use Valkyrja\Http\Client\Constant\ConfigName;
use Valkyrja\Http\Client\Constant\EnvName;

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
        ConfigName::ADAPTER_CLASS => EnvName::LOG_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::LOG_DRIVER_CLASS,
        ConfigName::LOGGER        => EnvName::LOG_LOGGER,
    ];

    public function __construct(
        public string|null $logger = null
    ) {
        parent::__construct(
            adapterClass: LogAdapter::class,
        );
    }
}
