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
        CKP::DRIVER,
        CKP::QUERY,
        CKP::QUERY_BUILDER,
        CKP::PERSISTER,
        CKP::RETRIEVER,
        CKP::REPOSITORY,
        CKP::CONNECTIONS,
        CKP::MIGRATIONS,
    ];

    /**
     * @inheritDoc
     */
    protected static array $envKeys = [
        CKP::DEFAULT       => EnvKey::ORM_DEFAULT,
        CKP::ADAPTER       => EnvKey::ORM_ADAPTER,
        CKP::DRIVER        => EnvKey::ORM_DRIVER,
        CKP::QUERY         => EnvKey::ORM_QUERY,
        CKP::QUERY_BUILDER => EnvKey::ORM_QUERY_BUILDER,
        CKP::PERSISTER     => EnvKey::ORM_PERSISTER,
        CKP::RETRIEVER     => EnvKey::ORM_RETRIEVER,
        CKP::REPOSITORY    => EnvKey::ORM_REPOSITORY,
        CKP::CONNECTIONS   => EnvKey::ORM_CONNECTIONS,
        CKP::MIGRATIONS    => EnvKey::ORM_MIGRATIONS,
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
     * The default driver.
     *
     * @var string
     */
    public string $driver;

    /**
     * The default query.
     *
     * @var string
     */
    public string $query;

    /**
     * The default query builder.
     *
     * @var string
     */
    public string $queryBuilder;

    /**
     * The default persister.
     *
     * @var string
     */
    public string $persister;

    /**
     * The default retriever.
     *
     * @var string
     */
    public string $retriever;

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
