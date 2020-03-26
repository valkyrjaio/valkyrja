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
use Valkyrja\Config\Models\ConfigModel as Model;

/**
 * Class ConnectionConfig.
 *
 * @author Melech Mizrachi
 */
class ConnectionConfig extends Model
{
    /**
     * The adapter.
     *
     * @var string
     */
    public string $adapter;

    /**
     * The driver.
     *
     * @var string
     */
    public string $driver;

    /**
     * The host.
     *
     * @var string
     */
    public string $host;

    /**
     * The port.
     *
     * @var string
     */
    public string $port;

    /**
     * The database.
     *
     * @var string
     */
    public string $db;

    /**
     * The username.
     *
     * @var string
     */
    public string $username;

    /**
     * The password.
     *
     * @var string
     */
    public string $password;

    /**
     * The socket.
     *
     * @var string
     */
    public string $socket;

    /**
     * The charset.
     *
     * @var string
     */
    public string $charset;

    /**
     * The collation.
     *
     * @var string
     */
    public string $collation;

    /**
     * The prefix.
     *
     * @var string
     */
    public string $prefix;

    /**
     * The strict flag.
     *
     * @var bool
     */
    public bool $strict;

    /**
     * The engine.
     *
     * @var string
     */
    public string $engine;

    /**
     * The schema.
     *
     * @var string
     */
    public string $schema;

    /**
     * The SSL mode.
     *
     * @var string
     */
    public string $sslMode;

    /**
     * ConnectionConfig constructor.
     *
     * @param string|null $driver
     * @param string|null $adapter
     * @param bool        $setDefaults [optional]
     */
    public function __construct(string $driver = null, string $adapter = null, bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setDriver($driver ?? CKP::MYSQL);
        $this->setAdapter($adapter ?? CKP::PDO);
        $this->setHost();
        $this->setPort();
        $this->setDb();
        $this->setUsername();
        $this->setPassword();
        $this->setSocket();
        $this->setCharset();
        $this->setCollation();
        $this->setPrefix();
        $this->setStrict();
        $this->setEngine();
        $this->setSchema();
        $this->setSslMode();
    }

    /**
     * Set the driver.
     *
     * @param string $driver [optional] The driver
     *
     * @return void
     */
    protected function setDriver(string $driver = CKP::MYSQL): void
    {
        $this->driver = $driver;
    }

    /**
     * Set the adapter.
     *
     * @param string $adapter [optional] The adapter
     *
     * @return void
     */
    protected function setAdapter(string $adapter = CKP::PDO): void
    {
        $this->adapter = $adapter;
    }

    /**
     * Set the host.
     *
     * @param string $host [optional] The host
     *
     * @return void
     */
    protected function setHost(string $host = '127.0.0.1'): void
    {
        $this->host = (string) env(EnvKey::DB_HOST, $host);
    }

    /**
     * Set the port.
     *
     * @param string $port [optional] The port
     *
     * @return void
     */
    protected function setPort(string $port = '3306'): void
    {
        $this->port = (string) env(EnvKey::DB_PORT, $port);
    }

    /**
     * Set the database.
     *
     * @param string $db [optional] The database
     *
     * @return void
     */
    protected function setDb(string $db = CKP::VALHALLA): void
    {
        $this->db = (string) env(EnvKey::DB_DATABASE, $db);
    }

    /**
     * Set the username.
     *
     * @param string $username [optional] The username
     *
     * @return void
     */
    protected function setUsername(string $username = CKP::VALHALLA): void
    {
        $this->username = (string) env(EnvKey::DB_USERNAME, $username);
    }

    /**
     * Set the password.
     *
     * @param string $password [optional] The password
     *
     * @return void
     */
    protected function setPassword(string $password = ''): void
    {
        $this->password = (string) env(EnvKey::DB_PASSWORD, $password);
    }

    /**
     * Set the socket.
     *
     * @param string $socket [optional] The socket
     *
     * @return void
     */
    protected function setSocket(string $socket = ''): void
    {
        $this->socket = (string) env(EnvKey::DB_SOCKET, $socket);
    }

    /**
     * Set the charset.
     *
     * @param string $charset [optional] The charset
     *
     * @return void
     */
    protected function setCharset(string $charset = 'utf8mb4'): void
    {
        $this->charset = (string) env(EnvKey::DB_CHARSET, $charset);
    }

    /**
     * Set the collation.
     *
     * @param string $collation [optional] The collation
     *
     * @return void
     */
    protected function setCollation(string $collation = 'utf8mb4_unicode_ci'): void
    {
        $this->collation = (string) env(EnvKey::DB_COLLATION, $collation);
    }

    /**
     * Set the prefix.
     *
     * @param string $prefix [optional] The prefix
     *
     * @return void
     */
    protected function setPrefix(string $prefix = ''): void
    {
        $this->prefix = (string) env(EnvKey::DB_PREFIX, $prefix);
    }

    /**
     * Set the strict flag.
     *
     * @param bool $strict [optional] The strict flag
     *
     * @return void
     */
    protected function setStrict(bool $strict = true): void
    {
        $this->strict = (bool) env(EnvKey::DB_STRICT, $strict);
    }

    /**
     * Set the engine.
     *
     * @param string $engine [optional] The engine
     *
     * @return void
     */
    protected function setEngine(string $engine = ''): void
    {
        $this->engine = (string) env(EnvKey::DB_ENGINE, $engine);
    }

    /**
     * Set the schema.
     *
     * @param string $schema [optional] The schema
     *
     * @return void
     */
    protected function setSchema(string $schema = 'public'): void
    {
        $this->schema = (string) env(EnvKey::DB_SCHEMA, $schema);
    }

    /**
     * Set the SSL mode.
     *
     * @param string $sslMode [optional] The SSL mode
     *
     * @return void
     */
    protected function setSslMode(string $sslMode = 'prefer'): void
    {
        $this->sslMode = (string) env(EnvKey::DB_SSL_MODE, $sslMode);
    }
}
