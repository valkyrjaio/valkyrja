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
use Valkyrja\JWT\Adapters\FirebaseAdapter;
use Valkyrja\JWT\Constants\Algo;
use Valkyrja\JWT\Drivers\Driver;
use Valkyrja\Support\Manager\Config\Config as Model;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     */
    protected static array $envKeys = [
        CKP::DEFAULT => EnvKey::JWT_DEFAULT,
        CKP::ADAPTER => EnvKey::JWT_ADAPTER,
        CKP::DRIVER  => EnvKey::JWT_DRIVER,
        CKP::ALGOS   => EnvKey::JWT_ALGOS,
    ];

    /**
     * @inheritDoc
     */
    public string $default = Algo::HS256;

    /**
     * @inheritDoc
     */
    public string $adapter = FirebaseAdapter::class;

    /**
     * @inheritDoc
     */
    public string $driver = Driver::class;

    /**
     * The algorithms.
     *
     * @var array[]
     */
    public array $algos = [
        Algo::HS256 => [
            CKP::ALGO    => Algo::HS256,
            CKP::ADAPTER => HSAdapter::class,
            CKP::DRIVER  => null,
            CKP::KEY     => '',
        ],
        Algo::RS256 => [
            CKP::ALGO        => Algo::RS256,
            CKP::ADAPTER     => RSAdapter::class,
            CKP::DRIVER      => null,
            CKP::PRIVATE_KEY => null,
            CKP::PUBLIC_KEY  => null,
            CKP::KEY_PATH    => null,
            CKP::PASSPHRASE  => null,
        ],
        Algo::EDDSA => [
            CKP::ALGO        => Algo::EDDSA,
            CKP::ADAPTER     => EdDSAAdapter::class,
            CKP::DRIVER      => null,
            CKP::PRIVATE_KEY => '',
            CKP::PUBLIC_KEY  => '',
        ],
    ];
}
