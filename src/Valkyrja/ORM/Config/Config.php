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
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;

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
        CKP::DEFAULT,
        CKP::ADAPTER,
        CKP::ADAPTERS,
        CKP::DRIVERS,
        CKP::REPOSITORY,
        CKP::CONNECTIONS,
        CKP::MIGRATIONS,
    ];

    /**
     * The model properties env keys.
     *
     * @var array
     */
    protected static array $envKeys = [
        CKP::DEFAULT     => EnvKey::ORM_DEFAULT,
        CKP::ADAPTER     => EnvKey::ORM_ADAPTER,
        CKP::ADAPTERS    => EnvKey::ORM_ADAPTERS,
        CKP::DRIVERS     => EnvKey::ORM_DRIVERS,
        CKP::REPOSITORY  => EnvKey::ORM_REPOSITORY,
        CKP::CONNECTIONS => EnvKey::ORM_CONNECTIONS,
        CKP::MIGRATIONS  => EnvKey::ORM_MIGRATIONS,
    ];

    /**
     * The default connection.
     *
     * @var string
     */
    public string $default;

    /**
     * The default adapter.
     *
     * @var string
     */
    public string $adapter;

    /**
     * The adapters.
     *
     * @var string[]
     */
    public array $adapters;

    /**
     * The drivers.
     *
     * @var string[]
     */
    public array $drivers;

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

    /**
     * The migrations.
     *
     * @var string[]
     */
    public array $migrations;
}
