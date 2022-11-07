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

namespace Valkyrja\Asset\Constants;

use Valkyrja\Asset\Adapters\DefaultAdapter;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT  = CKP::DEFAULT;
    public const ADAPTERS = [
        CKP::DEFAULT => DefaultAdapter::class,
    ];
    public const BUNDLES  = [
        CKP::DEFAULT => [
            CKP::ADAPTER  => CKP::DEFAULT,
            CKP::HOST     => '',
            CKP::PATH     => '/',
            CKP::MANIFEST => '/rev-manifest.json',
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTERS => self::ADAPTERS,
        CKP::BUNDLES  => self::BUNDLES,
    ];
}
