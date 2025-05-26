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

namespace Valkyrja\Jwt\Config;

use Valkyrja\Jwt\Adapter\Firebase\HsAdapter;
use Valkyrja\Jwt\Constant\ConfigName;
use Valkyrja\Jwt\Enum\Algorithm;

/**
 * Class HsConfiguration.
 *
 * @author Melech Mizrachi
 */
class HsConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ALGORITHM     => 'JWT_HS_ALGORITHM',
        ConfigName::ADAPTER_CLASS => 'JWT_HS_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'JWT_HS_DRIVER_CLASS',
        ConfigName::KEY           => 'JWT_HS_KEY',
    ];

    public function __construct(
        public string $key = 'example',
    ) {
        parent::__construct(
            algorithm: Algorithm::HS256,
            adapterClass: HsAdapter::class,
        );
    }
}
