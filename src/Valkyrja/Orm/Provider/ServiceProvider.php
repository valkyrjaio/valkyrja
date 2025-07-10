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
use Valkyrja\Application\Env;
use Valkyrja\Container\Contract\Container;
use Valkyrja\Container\Support\Provider;
use Valkyrja\Orm\Contract\Manager;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\InMemoryManager;
use Valkyrja\Orm\MysqlManager;
use Valkyrja\Orm\NullManager;
use Valkyrja\Orm\PgsqlManager;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Orm\SqliteManager;

/**
 * Class ServiceProvider.
 *
 * @author Melech Mizrachi
 */
final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            Manager::class         => [self::class, 'publishManager'],
            MysqlManager::class    => [self::class, 'publishMysqlManager'],
            PgsqlManager::class    => [self::class, 'publishPgsqlManager'],
            SqliteManager::class   => [self::class, 'publishSqliteManager'],
            InMemoryManager::class => [self::class, 'publishInMemoryManager'],
            NullManager::class     => [self::class, 'publishNullManager'],
            Repository::class      => [self::class, 'publishRepository'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            Manager::class,
            MysqlManager::class,
            PgsqlManager::class,
            SqliteManager::class,
            InMemoryManager::class,
            NullManager::class,
            Repository::class,
        ];
    }

    /**
     * Publish the manager service.
     */
    public static function publishManager(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var class-string<Manager> $default */
        $default = $env::ORM_DEFAULT_MANAGER;

        $container->setSingleton(
            Manager::class,
            $container->getSingleton($default),
        );
    }

    /**
     * Publish the mysql manager service.
     */
    public static function publishMysqlManager(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $db */
        $db = $env::ORM_MYSQL_DB;
        /** @var non-empty-string $host */
        $host = $env::ORM_MYSQL_HOST;
        /** @var positive-int $port */
        $port = $env::ORM_MYSQL_PORT;
        /** @var non-empty-string $user */
        $user = $env::ORM_MYSQL_USER;
        /** @var non-empty-string $password */
        $password = $env::ORM_MYSQL_PASSWORD;
        /** @var non-empty-string $charset */
        $charset = $env::ORM_MYSQL_CHARSET;
        /** @var non-empty-string|null $strict */
        $strict = $env::ORM_MYSQL_STRICT;
        /** @var non-empty-string|null $engine */
        $engine = $env::ORM_MYSQL_ENGINE;
        /** @var array<int, int|bool>|null $options */
        $options = $env::ORM_MYSQL_OPTIONS;

        $options ??= [
            PDO::ATTR_CASE              => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES  => false,
        ];

        $dsn = 'mysql'
            . ":dbname=$db}"
            . ";host=$host"
            . ";port=$port"
            . ";user=$user"
            . ";password=$password"
            . ";charset=$charset"
            . ($strict !== null ? ";strict=$strict" : '')
            . ($engine !== null ? ";engine=$engine" : '');

        $pdo = new PDO(
            dsn: $dsn,
            options: $options
        );

        $container->setSingleton(
            MysqlManager::class,
            new MysqlManager($pdo, $container)
        );
    }

    /**
     * Publish the pgsql manager service.
     */
    public static function publishPgsqlManager(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $db */
        $db = $env::ORM_PGSQL_DB;
        /** @var non-empty-string $host */
        $host = $env::ORM_PGSQL_HOST;
        /** @var positive-int $port */
        $port = $env::ORM_PGSQL_PORT;
        /** @var non-empty-string $user */
        $user = $env::ORM_PGSQL_USER;
        /** @var non-empty-string $password */
        $password = $env::ORM_PGSQL_PASSWORD;
        /** @var non-empty-string $charset */
        $charset = $env::ORM_PGSQL_CHARSET;
        /** @var non-empty-string $schema */
        $schema = $env::ORM_PGSQL_SCHEMA;
        /** @var non-empty-string $sslmode */
        $sslmode = $env::ORM_PGSQL_SSL_MODE;
        /** @var array<int, int|bool>|null $options */
        $options = $env::ORM_PGSQL_OPTIONS;

        $options ??= [
            PDO::ATTR_PERSISTENT        => true,
            PDO::ATTR_CASE              => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
        ];

        $dsn = 'pgsql'
            . ":dbname=$db}"
            . ";host=$host"
            . ";port=$port"
            . ";user=$user"
            . ";password=$password"
            . ";sslmode=$sslmode"
            . ";options='--client_encoding=$charset";

        $pdo = new PDO(
            dsn: $dsn,
            options: $options
        );

        $pdo->query("set search_path to $schema");

        $container->setSingleton(
            PgsqlManager::class,
            new PgsqlManager($pdo, $container)
        );
    }

    /**
     * Publish the sqlite manager service.
     */
    public static function publishSqliteManager(Container $container): void
    {
        $env = $container->getSingleton(Env::class);
        /** @var non-empty-string $db */
        $db = $env::ORM_SQLITE_DB;
        /** @var non-empty-string $host */
        $host = $env::ORM_SQLITE_HOST;
        /** @var positive-int $port */
        $port = $env::ORM_SQLITE_PORT;
        /** @var non-empty-string $user */
        $user = $env::ORM_SQLITE_USER;
        /** @var non-empty-string $password */
        $password = $env::ORM_SQLITE_PASSWORD;
        /** @var non-empty-string $charset */
        $charset = $env::ORM_SQLITE_CHARSET;
        /** @var array<int, int|bool>|null $options */
        $options = $env::ORM_SQLITE_OPTIONS;

        $options ??= [
            PDO::ATTR_CASE              => PDO::CASE_NATURAL,
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES  => false,
        ];

        $dsn = 'sqlite'
            . ":dbname=$db}"
            . ";host=$host"
            . ";port=$port"
            . ";user=$user"
            . ";charset=$charset"
            . ";password=$password";

        $pdo = new PDO(
            dsn: $dsn,
            options: $options
        );

        $container->setSingleton(
            SqliteManager::class,
            new SqliteManager($pdo, $container)
        );
    }

    /**
     * Publish the in memory manager service.
     */
    public static function publishInMemoryManager(Container $container): void
    {
        $container->setSingleton(
            InMemoryManager::class,
            new InMemoryManager()
        );
    }

    /**
     * Publish the null manager service.
     */
    public static function publishNullManager(Container $container): void
    {
        $container->setSingleton(
            NullManager::class,
            new NullManager()
        );
    }

    /**
     * Publish the repository service.
     */
    public static function publishRepository(Container $container): void
    {
        $container->setCallable(
            Repository::class,
            [self::class, 'createRepository'],
        );
    }

    /**
     * Create a repository service.
     *
     * @param class-string<Entity> $entity The entity
     */
    public static function createRepository(Container $container, Manager $manager, string $entity): Repository
    {
        return new Repository(
            manager: $manager,
            entity: $entity
        );
    }
}
