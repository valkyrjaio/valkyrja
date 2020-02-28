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
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Config as Model;

/**
 * Class Connection.
 *
 * @author Melech Mizrachi
 */
class Connection extends Model
{
    public string $adapter   = CKP::PDO;
    public string $driver    = CKP::MYSQL;
    public string $host      = '127.0.0.1';
    public string $port      = '3306';
    public string $db        = CKP::VALHALLA;
    public string $username  = CKP::VALHALLA;
    public string $password  = '';
    public string $socket    = '';
    public string $charset   = 'utf8mb4';
    public string $collation = 'utf8mb4_unicode_ci';
    public string $prefix    = '';
    public bool   $strict    = true;
    public string $engine    = '';
    public string $schema    = 'public';
    public string $sslMode   = 'prefer';

    /**
     * Connection constructor.
     *
     * @param string|null $driver
     * @param string|null $adapter
     */
    public function __construct(string $driver = null, string $adapter = null)
    {
        $this->driver     = $driver ?? $this->driver;
        $this->adapter    = $adapter ?? $this->adapter;
        $this->host       = (string) env(EnvKey::DB_HOST, $this->host);
        $this->port       = (string) env(EnvKey::DB_PORT, $this->port);
        $this->db         = (string) env(EnvKey::DB_DATABASE, $this->db);
        $this->username   = (string) env(EnvKey::DB_USERNAME, $this->username);
        $this->password   = (string) env(EnvKey::DB_PASSWORD, $this->password);
        $this->socket     = (string) env(EnvKey::DB_SOCKET, $this->socket);
        $this->charset    = (string) env(EnvKey::DB_CHARSET, $this->charset);
        $this->collation  = (string) env(EnvKey::DB_COLLATION, $this->collation);
        $this->prefix     = (string) env(EnvKey::DB_PREFIX, $this->prefix);
        $this->strict     = (bool) env(EnvKey::DB_STRICT, $this->strict);
        $this->engine     = (string) env(EnvKey::DB_ENGINE, $this->engine);
        $this->schema     = (string) env(EnvKey::DB_SCHEMA, $this->schema);
        $this->sslMode    = (string) env(EnvKey::DB_SSL_MODE, $this->sslMode);
    }
}
