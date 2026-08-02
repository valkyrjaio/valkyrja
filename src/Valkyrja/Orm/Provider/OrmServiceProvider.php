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

namespace Valkyrja\Orm\Provider;

use Override;
use PDO;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Orm\Data\Contract\OrmConfigContract;
use Valkyrja\Orm\Data\Contract\OrmMysqlConfigContract;
use Valkyrja\Orm\Data\Contract\OrmPgsqlConfigContract;
use Valkyrja\Orm\Data\Contract\OrmSqliteConfigContract;
use Valkyrja\Orm\Data\OrmConfig;
use Valkyrja\Orm\Data\OrmMysqlConfig;
use Valkyrja\Orm\Data\OrmPgsqlConfig;
use Valkyrja\Orm\Data\OrmSqliteConfig;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Manager\MysqlManager;
use Valkyrja\Orm\Manager\NullManager;
use Valkyrja\Orm\Manager\PgsqlManager;
use Valkyrja\Orm\Manager\SqliteManager;
use Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract;
use Valkyrja\Orm\Registry\EntityMetadataRegistry;
use Valkyrja\Orm\Repository\Repository;

class OrmServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the orm config service.
     */
    public static function publishConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof OrmConfigContract) {
            $container->setSingleton(OrmConfigContract::class, $config);

            return;
        }

        $container->setSingleton(OrmConfigContract::class, new OrmConfig());
    }

    /**
     * Publish the mysql config service.
     */
    public static function publishMysqlConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof OrmMysqlConfigContract) {
            $container->setSingleton(OrmMysqlConfigContract::class, $config);

            return;
        }

        $container->setSingleton(OrmMysqlConfigContract::class, new OrmMysqlConfig());
    }

    /**
     * Publish the pgsql config service.
     */
    public static function publishPgsqlConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof OrmPgsqlConfigContract) {
            $container->setSingleton(OrmPgsqlConfigContract::class, $config);

            return;
        }

        $container->setSingleton(OrmPgsqlConfigContract::class, new OrmPgsqlConfig());
    }

    /**
     * Publish the sqlite config service.
     */
    public static function publishSqliteConfig(ContainerContract $container): void
    {
        $config = $container->getSingleton(ConfigContract::class);

        if ($config instanceof OrmSqliteConfigContract) {
            $container->setSingleton(OrmSqliteConfigContract::class, $config);

            return;
        }

        $container->setSingleton(OrmSqliteConfigContract::class, new OrmSqliteConfig());
    }

    /**
     * Publish the manager service.
     */
    public static function publishManager(ContainerContract $container): void
    {
        $config = $container->getSingleton(OrmConfigContract::class);

        $container->setSingleton(
            ManagerContract::class,
            $container->getSingleton($config->defaultManager),
        );
    }

    /**
     * Publish the mysql manager service.
     */
    public static function publishMysqlManager(ContainerContract $container): void
    {
        $config = $container->getSingleton(OrmMysqlConfigContract::class);

        $db       = $config->mysqlDb;
        $host     = $config->mysqlHost;
        $port     = $config->mysqlPort;
        $user     = $config->mysqlUser;
        $password = $config->mysqlPassword;
        $charset  = $config->mysqlCharset;
        $strict   = $config->mysqlStrict;
        $engine   = $config->mysqlEngine;
        $options  = $config->mysqlOptions;

        $dsn = 'mysql'
            . ":dbname=$db"
            . ";host=$host"
            . ";port=$port"
            . ";user=$user"
            . ";password=$password"
            . ";charset=$charset"
            . ($strict !== null ? ";strict=$strict" : '')
            . ($engine !== null ? ";engine=$engine" : '');

        $container->setSingleton(
            MysqlManager::class,
            new MysqlManager(
                pdo: $container->getService(PDO::class, [$dsn, $options]),
                container: $container
            )
        );
    }

    /**
     * Publish the pgsql manager service.
     */
    public static function publishPgsqlManager(ContainerContract $container): void
    {
        $config = $container->getSingleton(OrmPgsqlConfigContract::class);

        $db       = $config->pgsqlDb;
        $host     = $config->pgsqlHost;
        $port     = $config->pgsqlPort;
        $user     = $config->pgsqlUser;
        $password = $config->pgsqlPassword;
        $charset  = $config->pgsqlCharset;
        $schema   = $config->pgsqlSchema;
        $sslmode  = $config->pgsqlSslMode;
        $options  = $config->pgsqlOptions;

        $dsn = 'pgsql'
            . ":dbname=$db"
            . ";host=$host"
            . ";port=$port"
            . ";user=$user"
            . ";password=$password"
            . ";sslmode=$sslmode"
            . ";options='--client_encoding=$charset'";

        $container->setSingleton(
            PgsqlManager::class,
            new PgsqlManager(
                pdo: $pdo = $container->getService(PDO::class, [$dsn, $options]),
                container: $container
            )
        );

        $pdo->query("set search_path to $schema");
    }

    /**
     * Publish the sqlite manager service.
     */
    public static function publishSqliteManager(ContainerContract $container): void
    {
        $config = $container->getSingleton(OrmSqliteConfigContract::class);

        $db       = $config->sqliteDb;
        $host     = $config->sqliteHost;
        $port     = $config->sqlitePort;
        $user     = $config->sqliteUser;
        $password = $config->sqlitePassword;
        $charset  = $config->sqliteCharset;
        $options  = $config->sqliteOptions;

        $dsn = 'sqlite'
            . ":dbname=$db"
            . ";host=$host"
            . ";port=$port"
            . ";user=$user"
            . ";charset=$charset"
            . ";password=$password";

        $container->setSingleton(
            SqliteManager::class,
            new SqliteManager(
                pdo: $container->getService(PDO::class, [$dsn, $options]),
                container: $container
            )
        );
    }

    /**
     * Publish the PDO service.
     */
    public static function publishPdo(ContainerContract $container): void
    {
        $container->bind(
            PDO::class,
            [self::class, 'createPdo'],
        );
    }

    /**
     * Create a PDO.
     *
     * @param array<array-key, mixed> $arguments
     */
    public static function createPdo(ContainerContract $container, array $arguments): PDO
    {
        [$dsn, $options] = $arguments;

        /**
         * @var non-empty-string     $dsn
         * @var array<int, int|bool> $options
         */

        return new PDO(
            dsn: $dsn,
            options: $options
        );
    }

    /**
     * Publish the null manager service.
     */
    public static function publishNullManager(ContainerContract $container): void
    {
        $container->setSingleton(
            NullManager::class,
            new NullManager(
                $container->getSingleton(EntityMetadataRegistryContract::class)
            )
        );
    }

    /**
     * Publish the entity metadata registry service.
     *
     * The framework registers an empty registry. An application replaces the
     * singleton in its own service provider to register an entity.
     */
    public static function publishEntityMetadataRegistry(ContainerContract $container): void
    {
        $container->setSingleton(
            EntityMetadataRegistryContract::class,
            new EntityMetadataRegistry()
        );
    }

    /**
     * Publish the repository service.
     */
    public static function publishRepository(ContainerContract $container): void
    {
        $container->bind(
            Repository::class,
            [self::class, 'createRepository'],
        );
    }

    /**
     * Create a repository service.
     *
     * @param array<array-key, mixed> $arguments
     */
    public static function createRepository(ContainerContract $container, array $arguments): Repository
    {
        [$manager, $entity, $registry] = $arguments;

        /**
         * @var ManagerContract                $manager
         * @var class-string<EntityContract>   $entity
         * @var EntityMetadataRegistryContract $registry
         */

        return new Repository(
            manager: $manager,
            entity: $entity,
            registry: $registry
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            OrmConfigContract::class              => [self::class, 'publishConfig'],
            OrmMysqlConfigContract::class         => [self::class, 'publishMysqlConfig'],
            OrmPgsqlConfigContract::class         => [self::class, 'publishPgsqlConfig'],
            OrmSqliteConfigContract::class        => [self::class, 'publishSqliteConfig'],
            ManagerContract::class                => [self::class, 'publishManager'],
            MysqlManager::class                   => [self::class, 'publishMysqlManager'],
            PgsqlManager::class                   => [self::class, 'publishPgsqlManager'],
            SqliteManager::class                  => [self::class, 'publishSqliteManager'],
            PDO::class                            => [self::class, 'publishPdo'],
            NullManager::class                    => [self::class, 'publishNullManager'],
            EntityMetadataRegistryContract::class => [self::class, 'publishEntityMetadataRegistry'],
            Repository::class                     => [self::class, 'publishRepository'],
        ];
    }
}
