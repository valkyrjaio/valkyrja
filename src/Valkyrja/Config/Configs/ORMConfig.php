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

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Configs\ORM\ConnectionsConfig;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\ConfigModel as Model;
use Valkyrja\ORM\Enums\Config;

/**
 * Class ORMConfig.
 *
 * @author Melech Mizrachi
 */
class ORMConfig extends Model
{
    /**
     * The default adapter.
     *
     * @var string
     */
    public string $default;

    /**
     * The adapters.
     *
     * @var string[]
     */
    public array $adapters;

    /**
     * The connections.
     *
     * @var ConnectionsConfig
     */
    public ConnectionsConfig  $connections;

    /**
     * ORMConfig constructor.
     */
    public function __construct()
    {
        $this->setDefault();
        $this->setAdapters();
        $this->setConnections();
    }

    /**
     * Set the default adapter.
     *
     * @param string $default [optional] The default adapter
     *
     * @return void
     */
    protected function setDefault(string $default = CKP::MYSQL): void
    {
        $this->default = (string) env(EnvKey::DB_CONNECTION, $default);
    }

    /**
     * Set the adapters.
     *
     * @param array $adapters [optional] The adapters
     *
     * @return void
     */
    protected function setAdapters(array $adapters = []): void
    {
        $this->adapters = (array) env(EnvKey::DB_ADAPTERS, array_merge(Config::ADAPTERS, $adapters));
    }

    /**
     * Set the connections.
     *
     * @param ConnectionsConfig|null $config [optional] The config
     *
     * @return void
     */
    protected function setConnections(ConnectionsConfig $config = null): void
    {
        $this->connections = $config ?? new ConnectionsConfig();
    }
}
