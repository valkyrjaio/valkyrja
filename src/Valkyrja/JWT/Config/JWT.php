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

namespace Valkyrja\JWT\Config;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\JWT\Adapters\Firebase\EdDSAAdapter;
use Valkyrja\JWT\Adapters\Firebase\HSAdapter;
use Valkyrja\JWT\Adapters\Firebase\RSAdapter;
use Valkyrja\JWT\Config\Config as Model;
use Valkyrja\JWT\Constants\Algo;
use Valkyrja\JWT\Constants\ConfigValue;

use function Valkyrja\env;

/**
 * Class JWT.
 */
class JWT extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->algos = [
            Algo::HS256 => [
                CKP::ALGO    => Algo::HS256,
                CKP::ADAPTER => env(EnvKey::JWT_HS_ADAPTER, HSAdapter::class),
                CKP::DRIVER  => env(EnvKey::JWT_HS_DRIVER),
                CKP::KEY     => env(EnvKey::JWT_HS_KEY, 'example'),
            ],
            Algo::RS256 => [
                CKP::ALGO        => Algo::RS256,
                CKP::ADAPTER     => env(EnvKey::JWT_RS_ADAPTER, RSAdapter::class),
                CKP::DRIVER      => env(EnvKey::JWT_RS_DRIVER),
                CKP::PRIVATE_KEY => env(EnvKey::JWT_RS_PRIVATE_KEY),
                CKP::PUBLIC_KEY  => env(EnvKey::JWT_RS_PUBLIC_KEY),
                CKP::KEY_PATH    => env(EnvKey::JWT_RS_KEY_PATH),
                CKP::PASSPHRASE  => env(EnvKey::JWT_RS_PASSPHRASE),
            ],
            Algo::EDDSA => [
                CKP::ALGO        => Algo::EDDSA,
                CKP::ADAPTER     => env(EnvKey::JWT_EDDSA_ADAPTER, EdDSAAdapter::class),
                CKP::DRIVER      => env(EnvKey::JWT_EDDSA_DRIVER),
                CKP::PRIVATE_KEY => env(EnvKey::JWT_EDDSA_PRIVATE_KEY),
                CKP::PUBLIC_KEY  => env(EnvKey::JWT_EDDSA_PUBLIC_KEY),
            ],
        ];
    }
}
