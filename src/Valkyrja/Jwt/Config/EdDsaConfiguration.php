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

use Valkyrja\Jwt\Adapter\Firebase\EdDsaAdapter;
use Valkyrja\Jwt\Constant\ConfigName;
use Valkyrja\Jwt\Constant\EnvName;
use Valkyrja\Jwt\Enum\Algorithm;

/**
 * Class EdDsaConfiguration.
 *
 * @author Melech Mizrachi
 */
class EdDsaConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ALGORITHM     => EnvName::EDDSA_ALGORITHM,
        ConfigName::ADAPTER_CLASS => EnvName::EDDSA_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::EDDSA_DRIVER_CLASS,
        ConfigName::PRIVATE_KEY   => EnvName::EDDSA_PRIVATE_KEY,
        ConfigName::PUBLIC_KEY    => EnvName::EDDSA_PUBLIC_KEY,
    ];

    public function __construct(
        public string $privateKey = '',
        public string $publicKey = '',
    ) {
        parent::__construct(
            algorithm: Algorithm::EdDSA,
            adapterClass: EdDsaAdapter::class,
        );
    }
}
