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

namespace Valkyrja\Jwt\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Jwt\Adapters\Firebase\EdDsaAdapter;
use Valkyrja\Jwt\Adapters\Firebase\HsAdapter;
use Valkyrja\Jwt\Adapters\Firebase\RsAdapter;
use Valkyrja\Jwt\Adapters\FirebaseAdapter;
use Valkyrja\Jwt\Drivers\Driver;

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
            CKP::ADAPTER => HsAdapter::class,
            CKP::DRIVER  => null,
            CKP::KEY     => '',
        ],
        Algo::RS256 => [
            CKP::ALGO        => Algo::RS256,
            CKP::ADAPTER     => RsAdapter::class,
            CKP::DRIVER      => null,
            CKP::PRIVATE_KEY => null,
            CKP::PUBLIC_KEY  => null,
            CKP::KEY_PATH    => null,
            CKP::PASSPHRASE  => null,
        ],
        Algo::EDDSA => [
            CKP::ALGO        => Algo::EDDSA,
            CKP::ADAPTER     => EdDsaAdapter::class,
            CKP::DRIVER      => null,
            CKP::PRIVATE_KEY => '',
            CKP::PUBLIC_KEY  => '',
        ],
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::DEFAULT => self::DEFAULT,
        CKP::ADAPTER => self::ADAPTER,
        CKP::DRIVER  => self::DRIVER,
        CKP::ALGOS   => self::ALGOS,
    ];
}
