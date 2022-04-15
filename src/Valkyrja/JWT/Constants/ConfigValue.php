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

namespace Valkyrja\JWT\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\JWT\Adapters\Firebase\EdDSAAdapter;
use Valkyrja\JWT\Adapters\Firebase\HSAdapter;
use Valkyrja\JWT\Adapters\Firebase\RSAdapter;
use Valkyrja\JWT\Adapters\FirebaseAdapter;
use Valkyrja\JWT\Drivers\Driver;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT = Algo::HS256;
    public const ADAPTER = FirebaseAdapter::class;
    public const DRIVER  = Driver::class;
    public const ALGOS   = [
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

    public static array $defaults = [
        CKP::DEFAULT => self::DEFAULT,
        CKP::ADAPTER => self::ADAPTER,
        CKP::DRIVER  => self::DRIVER,
        CKP::ALGOS   => self::ALGOS,
    ];
}
