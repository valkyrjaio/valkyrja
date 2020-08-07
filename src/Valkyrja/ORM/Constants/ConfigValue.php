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

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\ORM\Adapters\PDOAdapter;
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
    public const REPOSITORY  = Repository::class;
    public const CONNECTIONS = [
        CKP::MYSQL => [
            CKP::ADAPTER  => CKP::PDO,
            CKP::DRIVER   => CKP::MYSQL,
            CKP::HOST     => '127.0.0.1',
            CKP::PORT     => '3306',
            CKP::DB       => CKP::VALHALLA,
            CKP::USERNAME => CKP::VALHALLA,
            CKP::PASSWORD => '',
            CKP::CHARSET  => 'utf8mb4',
            CKP::OPTIONS  => [],
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT     => self::DEFAULT,
        CKP::ADAPTERS    => self::ADAPTERS,
        CKP::REPOSITORY  => self::REPOSITORY,
        CKP::CONNECTIONS => self::CONNECTIONS,
    ];
}
