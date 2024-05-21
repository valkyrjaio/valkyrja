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

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Jwt\Adapters\Firebase\EdDsaAdapter;
use Valkyrja\Jwt\Adapters\Firebase\HsAdapter;
use Valkyrja\Jwt\Adapters\Firebase\RsAdapter;
use Valkyrja\Jwt\Config\Config as Model;
use Valkyrja\Jwt\Constants\Algo;
use Valkyrja\Jwt\Constants\ConfigValue;

use function Valkyrja\env;

/**
 * Class Jwt.
 */
class Jwt extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->algos = [
            Algo::HS256 => [
                CKP::ALGO    => Algo::HS256,
                CKP::ADAPTER => env(EnvKey::JWT_HS_ADAPTER, HsAdapter::class),
                CKP::DRIVER  => env(EnvKey::JWT_HS_DRIVER),
                CKP::KEY     => env(EnvKey::JWT_HS_KEY, 'example'),
            ],
            Algo::RS256 => [
                CKP::ALGO        => Algo::RS256,
                CKP::ADAPTER     => env(EnvKey::JWT_RS_ADAPTER, RsAdapter::class),
                CKP::DRIVER      => env(EnvKey::JWT_RS_DRIVER),
                CKP::PRIVATE_KEY => env(EnvKey::JWT_RS_PRIVATE_KEY),
                CKP::PUBLIC_KEY  => env(EnvKey::JWT_RS_PUBLIC_KEY),
                CKP::KEY_PATH    => env(EnvKey::JWT_RS_KEY_PATH),
                CKP::PASSPHRASE  => env(EnvKey::JWT_RS_PASSPHRASE),
            ],
            Algo::EDDSA => [
                CKP::ALGO        => Algo::EDDSA,
                CKP::ADAPTER     => env(EnvKey::JWT_EDDSA_ADAPTER, EdDsaAdapter::class),
                CKP::DRIVER      => env(EnvKey::JWT_EDDSA_DRIVER),
                CKP::PRIVATE_KEY => env(EnvKey::JWT_EDDSA_PRIVATE_KEY),
                CKP::PUBLIC_KEY  => env(EnvKey::JWT_EDDSA_PUBLIC_KEY),
            ],
        ];
    }
}
