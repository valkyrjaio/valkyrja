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

namespace Valkyrja\ORM\Enums;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\ORM\Adapters\PDOAdapter;
use Valkyrja\ORM\Repositories\Repository;

/**
 * Enum ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const CONNECTION  = CKP::MYSQL;
    public const ADAPTERS    = [
        CKP::PDO => PDOAdapter::class,
    ];
    public const REPOSITORY  = Repository::class;
    public const CONNECTIONS = [
        CKP::MYSQL => [
            CKP::ADAPTER => CKP::PDO,
        ],
    ];

    public static array $defaults = [
        CKP::CONNECTION  => self::CONNECTION,
        CKP::ADAPTERS    => self::ADAPTERS,
        CKP::REPOSITORY  => self::REPOSITORY,
        CKP::CONNECTIONS => self::CONNECTIONS,
    ];
}
