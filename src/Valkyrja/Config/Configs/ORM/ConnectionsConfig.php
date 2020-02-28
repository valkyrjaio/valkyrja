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
    public ConnectionConfig $mysql;
    public ConnectionConfig $pgsql;
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
     * @return void
     */
    protected function setMysqlConnection(): void
    {
        $this->mysql = new ConnectionConfig();
    }

    /**
     * Set the pgsql connection.
     *
     * @return void
     */
    protected function setPgsqlConnection(): void
    {
        $this->pgsql = new ConnectionConfig(CKP::PGSQL);
    }

    /**
     * Set the sqlsrv connection.
     *
     * @return void
     */
    protected function setSqlsrvConnection(): void
    {
        $this->sqlsrv = new ConnectionConfig(CKP::SQLSRV);
    }
}
