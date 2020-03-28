<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Configs\ORM;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Models\Model;

/**
 * Class Connections.
 *
 * @author Melech Mizrachi
 */
class Connections extends Model
{
    /**
     * The mysql connection.
     *
     * @var Connection
     */
    public Connection $mysql;

    /**
     * The pgsql connection.
     *
     * @var Connection
     */
    public Connection $pgsql;

    /**
     * The sqlsrv connection.
     *
     * @var Connection
     */
    public Connection $sqlsrv;

    /**
     * Connections constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setMysqlConnection();
        $this->setPgsqlConnection();
        $this->setSqlsrvConnection();
    }

    /**
     * Set the mysql connection.
     *
     * @param Connection|null $config [optional] The config
     *
     * @return void
     */
    protected function setMysqlConnection(Connection $config = null): void
    {
        $this->mysql = $config ?? new Connection();
    }

    /**
     * Set the pgsql connection.
     *
     * @param Connection|null $config [optional] The config
     *
     * @return void
     */
    protected function setPgsqlConnection(Connection $config = null): void
    {
        $this->pgsql = $config ?? new Connection(CKP::PGSQL);
    }

    /**
     * Set the sqlsrv connection.
     *
     * @param Connection|null $config [optional] The config
     *
     * @return void
     */
    protected function setSqlsrvConnection(Connection $config = null): void
    {
        $this->sqlsrv = $config ?? new Connection(CKP::SQLSRV);
    }
}
