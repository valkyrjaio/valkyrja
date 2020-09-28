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

namespace Valkyrja\ORM\Constants;

use PDO;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\ORM\Adapters\PDOAdapter;
use Valkyrja\ORM\Drivers\Driver;
use Valkyrja\ORM\Repositories\Repository;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT     = CKP::MYSQL;
    public const ADAPTERS    = [
        CKP::PDO => PDOAdapter::class,
    ];
    public const DRIVERS     = [
        CKP::DEFAULT => Driver::class,
    ];
    public const REPOSITORY  = Repository::class;
    public const CONNECTIONS = [
        CKP::MYSQL => [
            CKP::ADAPTER    => CKP::PDO,
            CKP::DRIVER     => CKP::DEFAULT,
            CKP::PDO_DRIVER => CKP::MYSQL,
            CKP::HOST       => '127.0.0.1',
            CKP::PORT       => '3306',
            CKP::DB         => CKP::VALHALLA,
            CKP::USERNAME   => CKP::VALHALLA,
            CKP::PASSWORD   => '',
            CKP::CHARSET    => 'utf8mb4',
            CKP::OPTIONS    => [],
        ],
        CKP::PGSQL => [
            CKP::ADAPTER    => CKP::PDO,
            CKP::DRIVER     => CKP::DEFAULT,
            CKP::PDO_DRIVER => CKP::PGSQL,
            CKP::HOST       => '127.0.0.1',
            CKP::PORT       => '3306',
            CKP::DB         => CKP::VALHALLA,
            CKP::USERNAME   => CKP::VALHALLA,
            CKP::PASSWORD   => '',
            CKP::SCHEMA     => 'public',
            CKP::SSL_MODE   => 'prefer',
            CKP::CHARSET    => 'utf8',
            CKP::OPTIONS    => [
                PDO::ATTR_PERSISTENT        => true,
                PDO::ATTR_CASE              => PDO::CASE_NATURAL,
                PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ],
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT     => self::DEFAULT,
        CKP::ADAPTERS    => self::ADAPTERS,
        CKP::DRIVERS     => self::DRIVERS,
        CKP::REPOSITORY  => self::REPOSITORY,
        CKP::CONNECTIONS => self::CONNECTIONS,
    ];
}
