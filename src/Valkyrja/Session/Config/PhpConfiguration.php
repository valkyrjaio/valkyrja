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

use Valkyrja\Session\Adapter\PHPAdapter;
use Valkyrja\Session\Constant\ConfigName;
use Valkyrja\Session\Constant\EnvName;

/**
 * Class PhpConfiguration.
 *
 * @author Melech Mizrachi
 */
class PhpConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => EnvName::PHP_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::PHP_DRIVER_CLASS,
        ConfigName::ID            => EnvName::PHP_ID,
        ConfigName::NAME          => EnvName::PHP_NAME,
    ];

    public function __construct(
        public CookieParamsConfiguration $cookieParams = new CookieParamsConfiguration(),
    ) {
        parent::__construct(
            adapterClass: PHPAdapter::class,
            id: 'VALKYRJA_ID',
            name: 'VALKYRJA_SESSION',
        );
    }

    /**
     * @inheritDoc
     */
    public function setPropertiesFromEnv(string $env): void
    {
        $this->cookieParams->setPropertiesFromEnv($env);

        parent::setPropertiesFromEnv($env);
    }
}
