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

namespace Valkyrja\Orm\Pdo;

use PDO as BasePDO;
use Valkyrja\Orm\Config\PgsqlConnection;

/**
 * Class PgsqlPdo.
 *
 * @author Melech Mizrachi
 */
class PgsqlPdo extends Pdo
{
    /**
     * The default options.
     *
     * @var array<int, int|bool>
     */
    protected array $defaultOptions = [
        BasePDO::ATTR_PERSISTENT        => true,
        BasePDO::ATTR_CASE              => BasePDO::CASE_NATURAL,
        BasePDO::ATTR_ERRMODE           => BasePDO::ERRMODE_EXCEPTION,
        BasePDO::ATTR_ORACLE_NULLS      => BasePDO::NULL_NATURAL,
        BasePDO::ATTR_STRINGIFY_FETCHES => false,
    ];

    /**
     * PgSqlPDO constructor.
     */
    public function __construct(PgsqlConnection $config)
    {
        $dsn = $this->getDsnPart($config, 'sslmode')
            . ";options='--client_encoding=$config->charset'";

        parent::__construct($config, 'pgsql', $dsn);
    }
}
