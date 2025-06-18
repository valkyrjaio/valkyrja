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

namespace Valkyrja\Session\Config;

use Valkyrja\Session\Adapter\CookieAdapter;
use Valkyrja\Session\Constant\ConfigName;
use Valkyrja\Session\Constant\EnvName;

/**
 * Class CookieConfiguration.
 *
 * @author Melech Mizrachi
 */
class CookieConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => EnvName::COOKIE_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::COOKIE_DRIVER_CLASS,
    ];

    public function __construct()
    {
        parent::__construct(
            adapterClass: CookieAdapter::class,
        );
    }
}
