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

namespace Valkyrja\Asset\Constant;

use Valkyrja\Asset\Adapter\DefaultAdapter;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT  = 'default';
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

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTERS => self::ADAPTERS,
        CKP::BUNDLES  => self::BUNDLES,
    ];
}
