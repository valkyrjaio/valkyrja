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

use Valkyrja\Jwt\Adapter\NullAdapter;
use Valkyrja\Jwt\Constant\ConfigName;
use Valkyrja\Jwt\Constant\EnvName;
use Valkyrja\Jwt\Enum\Algorithm;

/**
 * Class NullConfiguration.
 *
 * @author Melech Mizrachi
 */
class NullConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => EnvName::NULL_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::NULL_DRIVER_CLASS,
    ];

    public function __construct()
    {
        parent::__construct(
            algorithm: Algorithm::ES256,
            adapterClass: NullAdapter::class,
        );
    }
}
