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

namespace Valkyrja\Crypt\Config;

use Valkyrja\Crypt\Adapter\SodiumAdapter;
use Valkyrja\Crypt\Constant\ConfigName;
use Valkyrja\Crypt\Constant\EnvName;

/**
 * Class SodiumConfiguration.
 *
 * @author Melech Mizrachi
 */
class SodiumConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => EnvName::SODIUM_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::SODIUM_DRIVER_CLASS,
        ConfigName::KEY           => EnvName::SODIUM_KEY,
        ConfigName::KEY_PATH      => EnvName::SODIUM_KEY_PATH,
    ];

    public function __construct(
        string $key = '',
        public string|null $keyPath = null,
    ) {
        parent::__construct(
            key: $key,
            adapterClass: SodiumAdapter::class,
        );
    }
}
