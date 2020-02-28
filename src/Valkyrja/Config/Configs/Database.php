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
use Valkyrja\Config\Models\Config as Model;
use Valkyrja\ORM\Enums\Config;

/**
 * Class Database.
 *
 * @author Melech Mizrachi
 */
class Database extends Model
{
    public string       $default  = CKP::MYSQL;
    public array        $adapters = [];
    public Connections  $connections;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->default  = env(EnvKey::DB_CONNECTION, $this->default);
        $this->adapters = env(EnvKey::DB_ADAPTERS, array_merge(Config::ADAPTERS, $this->adapters));

        $this->setConnections();
    }

    /**
     * Set connections.
     *
     * @return void
     */
    protected function setConnections(): void
    {
        $this->connections = new Connections();
    }
}
