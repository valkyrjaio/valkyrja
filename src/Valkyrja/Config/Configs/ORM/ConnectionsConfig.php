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
use Valkyrja\Config\Models\ConfigModel as Model;

/**
 * Class ConnectionsConfig.
 *
 * @author Melech Mizrachi
 */
class ConnectionsConfig extends Model
{
    /**
     * The mysql connection.
     *
     * @var ConnectionConfig
     */
    public ConnectionConfig $mysql;

    /**
     * The pgsql connection.
     *
     * @var ConnectionConfig
     */
    public ConnectionConfig $pgsql;

    /**
     * The sqlsrv connection.
     *
     * @var ConnectionConfig
     */
    public ConnectionConfig $sqlsrv;

    /**
     * ConnectionsConfig constructor.
     */
    public function __construct()
    {
        $this->setMysqlConnection();
        $this->setPgsqlConnection();
        $this->setSqlsrvConnection();
    }

    /**
     * Set the mysql connection.
     *
     * @param ConnectionConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setMysqlConnection(ConnectionConfig $config = null): void
    {
        $this->mysql = $config ?? new ConnectionConfig();
    }

    /**
     * Set the pgsql connection.
     *
     * @param ConnectionConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setPgsqlConnection(ConnectionConfig $config = null): void
    {
        $this->pgsql = $config ?? new ConnectionConfig(CKP::PGSQL);
    }

    /**
     * Set the sqlsrv connection.
     *
     * @param ConnectionConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setSqlsrvConnection(ConnectionConfig $config = null): void
    {
        $this->sqlsrv = $config ?? new ConnectionConfig(CKP::SQLSRV);
    }
}
