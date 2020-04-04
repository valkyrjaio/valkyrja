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

namespace Valkyrja\ORM\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Config\Enums\EnvKey;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * Array of properties in the model.
     *
     * @var array
     */
    protected static array $modelProperties = [
        CKP::CONNECTION,
        CKP::ADAPTERS,
        CKP::REPOSITORY,
        CKP::CONNECTIONS,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::CONNECTION  => EnvKey::DB_CONNECTION,
        CKP::ADAPTERS    => EnvKey::DB_ADAPTERS,
        CKP::REPOSITORY  => EnvKey::DB_REPOSITORY,
        CKP::CONNECTIONS => EnvKey::DB_CONNECTIONS,
    ];

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
     * @var array
     */
    public array $connections;
}
