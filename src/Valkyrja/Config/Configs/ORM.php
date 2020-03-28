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

use Valkyrja\Config\Configs\ORM\Connections;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Model;
use Valkyrja\ORM\Enums\Config;
use Valkyrja\ORM\Repositories\Repository;

use function Valkyrja\env;

/**
 * Class ORM
 *
 * @author Melech Mizrachi
 */
class ORM extends Model
{
    /**
     * The default adapter.
     *
     * @var string
     */
    public string $connection;

    /**
     * The adapters.
     *
     * @var string[]
     */
    public array $adapters;

    /**
     * The default repository to use for all entities.
     *
     * @var string
     */
    public string $repository;

    /**
     * The connections.
     *
     * @var Connections
     */
    public Connections  $connections;

    /**
     * ORM constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setConnection();
        $this->setAdapters();
        $this->setRepository();
        $this->setConnections();
    }

    /**
     * Set the default adapter.
     *
     * @param string $connection [optional] The default adapter
     *
     * @return void
     */
    protected function setConnection(string $connection = CKP::MYSQL): void
    {
        $this->connection = (string) env(EnvKey::DB_CONNECTION, $connection);
    }

    /**
     * Set the adapters.
     *
     * @param array $adapters [optional] The adapters
     *
     * @return void
     */
    protected function setAdapters(array $adapters = Config::ADAPTERS): void
    {
        $this->adapters = (array) env(EnvKey::DB_ADAPTERS, $adapters);
    }

    /**
     * Set the default repository to use for all entities.
     *
     * @param string $repository
     *
     * @return void
     */
    protected function setRepository(string $repository = Repository::class): void
    {
        $this->repository = (string) env(EnvKey::DB_REPOSITORY, $repository);
    }

    /**
     * Set the connections.
     *
     * @param Connections|null $config [optional] The config
     *
     * @return void
     */
    protected function setConnections(Connections $config = null): void
    {
        $this->connections = $config ?? new Connections();
    }
}
