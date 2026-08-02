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

namespace Valkyrja\Tests\Unit\Orm\Provider;

use Override;
use PDO;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\Contract\ConfigContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Orm\Data\Contract\OrmConfigContract;
use Valkyrja\Orm\Data\Contract\OrmMysqlConfigContract;
use Valkyrja\Orm\Data\Contract\OrmPgsqlConfigContract;
use Valkyrja\Orm\Data\Contract\OrmSqliteConfigContract;
use Valkyrja\Orm\Data\OrmConfig;
use Valkyrja\Orm\Data\OrmMysqlConfig;
use Valkyrja\Orm\Data\OrmPgsqlConfig;
use Valkyrja\Orm\Data\OrmSqliteConfig;
use Valkyrja\Orm\Entity\Abstract\Entity;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Manager\MysqlManager;
use Valkyrja\Orm\Manager\NullManager;
use Valkyrja\Orm\Manager\PgsqlManager;
use Valkyrja\Orm\Manager\SqliteManager;
use Valkyrja\Orm\Provider\OrmServiceProvider;
use Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract;
use Valkyrja\Orm\Registry\EntityMetadataRegistry;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Fixtures\Orm\Data\OrmConfigFixture;
use Valkyrja\Tests\Fixtures\Orm\PdoFixture;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = OrmServiceProvider::class;

    /**
     * Every manager reads its connection config, so bind the framework defaults
     * for each publisher test.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->container->setSingleton(OrmConfigContract::class, new OrmConfig());
        $this->container->setSingleton(OrmMysqlConfigContract::class, new OrmMysqlConfig());
        $this->container->setSingleton(OrmPgsqlConfigContract::class, new OrmPgsqlConfig());
        $this->container->setSingleton(OrmSqliteConfigContract::class, new OrmSqliteConfig());
    }

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(OrmConfigContract::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(OrmMysqlConfigContract::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(OrmPgsqlConfigContract::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(OrmSqliteConfigContract::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(ManagerContract::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(MysqlManager::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(PgsqlManager::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(SqliteManager::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(PDO::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(NullManager::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(EntityMetadataRegistryContract::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(Repository::class, new OrmServiceProvider()->publishers());
    }

    public function testPublishConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new Config());

        $callback = new OrmServiceProvider()->publishers()[OrmConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(OrmConfigContract::class, $config = $this->container->getSingleton(OrmConfigContract::class));
        self::assertSame(MysqlManager::class, $config->defaultManager);
    }

    public function testPublishConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new OrmConfigFixture());

        $callback = new OrmServiceProvider()->publishers()[OrmConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(OrmConfigContract::class, $config = $this->container->getSingleton(OrmConfigContract::class));
        self::assertSame(SqliteManager::class, $config->defaultManager);
    }

    public function testPublishMysqlConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new Config());

        $callback = new OrmServiceProvider()->publishers()[OrmMysqlConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(OrmMysqlConfigContract::class, $config = $this->container->getSingleton(OrmMysqlConfigContract::class));
        self::assertSame('valkyrja', $config->mysqlDb);
    }

    public function testPublishMysqlConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new OrmConfigFixture());

        $callback = new OrmServiceProvider()->publishers()[OrmMysqlConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(OrmMysqlConfigContract::class, $config = $this->container->getSingleton(OrmMysqlConfigContract::class));
        self::assertSame('test-mysql-db', $config->mysqlDb);
    }

    public function testPublishPgsqlConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new Config());

        $callback = new OrmServiceProvider()->publishers()[OrmPgsqlConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(OrmPgsqlConfigContract::class, $config = $this->container->getSingleton(OrmPgsqlConfigContract::class));
        self::assertSame('public', $config->pgsqlSchema);
    }

    public function testPublishPgsqlConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new OrmConfigFixture());

        $callback = new OrmServiceProvider()->publishers()[OrmPgsqlConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(OrmPgsqlConfigContract::class, $config = $this->container->getSingleton(OrmPgsqlConfigContract::class));
        self::assertSame('test', $config->pgsqlSchema);
    }

    public function testPublishSqliteConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new Config());

        $callback = new OrmServiceProvider()->publishers()[OrmSqliteConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(OrmSqliteConfigContract::class, $config = $this->container->getSingleton(OrmSqliteConfigContract::class));
        self::assertSame('valkyrja', $config->sqliteDb);
    }

    public function testPublishSqliteConfigWithApplicationConfig(): void
    {
        $this->container->setSingleton(ConfigContract::class, new OrmConfigFixture());

        $callback = new OrmServiceProvider()->publishers()[OrmSqliteConfigContract::class];
        $callback($this->container);

        self::assertInstanceOf(OrmSqliteConfigContract::class, $config = $this->container->getSingleton(OrmSqliteConfigContract::class));
        self::assertSame('test-sqlite-db', $config->sqliteDb);
    }

    public function testPublishEntityMetadataRegistry(): void
    {
        $callback = new OrmServiceProvider()->publishers()[EntityMetadataRegistryContract::class];
        $callback($this->container);

        self::assertInstanceOf(
            EntityMetadataRegistry::class,
            $this->container->getSingleton(EntityMetadataRegistryContract::class)
        );
    }

    /**
     * @throws Exception
     */
    public function testPublishManager(): void
    {
        $this->container->setSingleton(MysqlManager::class, self::createStub(MysqlManager::class));

        $callback = new OrmServiceProvider()->publishers()[ManagerContract::class];
        $callback($this->container);

        self::assertInstanceOf(MysqlManager::class, $this->container->getSingleton(ManagerContract::class));
    }

    public function testPublishMysqlManager(): void
    {
        $this->container->bind(
            PDO::class,
            static fn (ContainerContract $container, array $arguments): PDO => new PdoFixture('sqlite::memory:')
        );

        $callback = new OrmServiceProvider()->publishers()[MysqlManager::class];
        $callback($this->container);

        self::assertInstanceOf(MysqlManager::class, $this->container->getSingleton(MysqlManager::class));
    }

    /**
     * The optional strict mode and storage engine default to null and are then left out of
     * the DSN entirely; when set they are appended to it.
     */
    public function testPublishMysqlManagerWithStrictAndEngine(): void
    {
        $this->container->setSingleton(
            OrmMysqlConfigContract::class,
            new OrmMysqlConfig(mysqlStrict: true, mysqlEngine: 'InnoDB')
        );

        $dsn = null;

        $this->container->bind(
            PDO::class,
            static function (ContainerContract $container, array $arguments) use (&$dsn): PDO {
                $dsn = $arguments[0] ?? null;

                return new PdoFixture('sqlite::memory:');
            }
        );

        $callback = new OrmServiceProvider()->publishers()[MysqlManager::class];
        $callback($this->container);

        self::assertInstanceOf(MysqlManager::class, $this->container->getSingleton(MysqlManager::class));
        self::assertIsString($dsn);
        self::assertStringContainsString(';strict=1', $dsn);
        self::assertStringContainsString(';engine=InnoDB', $dsn);
    }

    public function testPublishMysqlManagerDsn(): void
    {
        self::assertSame(
            'mysql:dbname=valkyrja'
            . ';host=127.0.0.1'
            . ';port=3306'
            . ';user=valkyrja'
            . ';password=mysql-password'
            . ';charset=utf8mb4',
            $this->captureDsnFor(MysqlManager::class)
        );
    }

    public function testPublishSqliteManagerDsn(): void
    {
        self::assertSame(
            'sqlite:dbname=valkyrja'
            . ';host=127.0.0.1'
            . ';port=3306'
            . ';user=valkyrja'
            . ';charset=utf8'
            . ';password=sqlite-password',
            $this->captureDsnFor(SqliteManager::class)
        );
    }

    public function testPublishPgsqlManagerDsn(): void
    {
        self::assertSame(
            'pgsql:dbname=valkyrja'
            . ';host=127.0.0.1'
            . ';port=6379'
            . ';user=valkyrja'
            . ';password=pgsql-password'
            . ';sslmode=prefer'
            . ";options='--client_encoding=utf8'",
            $this->captureDsnFor(PgsqlManager::class)
        );
    }

    public function testPublishPgsqlManager(): void
    {
        $this->container->bind(
            PDO::class,
            static fn (ContainerContract $container, array $arguments): PDO => new PdoFixture('sqlite::memory:')
        );

        $callback = new OrmServiceProvider()->publishers()[PgsqlManager::class];
        $callback($this->container);

        self::assertInstanceOf(PgsqlManager::class, $this->container->getSingleton(PgsqlManager::class));
    }

    public function testPublishSqliteManager(): void
    {
        $this->container->bind(
            PDO::class,
            static fn (ContainerContract $container, array $arguments): PDO => new PdoFixture('sqlite::memory:')
        );

        $callback = new OrmServiceProvider()->publishers()[SqliteManager::class];
        $callback($this->container);

        self::assertInstanceOf(SqliteManager::class, $this->container->getSingleton(SqliteManager::class));
    }

    public function testPublishNullManager(): void
    {
        $this->container->setSingleton(EntityMetadataRegistryContract::class, new EntityMetadataRegistry());

        $callback = new OrmServiceProvider()->publishers()[NullManager::class];
        $callback($this->container);

        self::assertInstanceOf(NullManager::class, $this->container->getSingleton(NullManager::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishRepository(): void
    {
        $manager  = self::createStub(MysqlManager::class);
        $entity   = Entity::class;
        $registry = new EntityMetadataRegistry();

        $callback = new OrmServiceProvider()->publishers()[Repository::class];
        $callback($this->container);

        self::assertInstanceOf(
            Repository::class,
            $this->container->getService(Repository::class, [$manager, $entity, $registry])
        );
    }

    /**
     * @throws Exception
     */
    public function testPublishPdo(): void
    {
        $dsn     = 'sqlite::memory:';
        $options = [];

        $callback = new OrmServiceProvider()->publishers()[PDO::class];
        $callback($this->container);

        self::assertInstanceOf(PDO::class, $this->container->getService(PDO::class, [$dsn, $options]));
    }

    /**
     * Publish a manager and return the DSN it passed to PDO.
     *
     * @param class-string $manager The manager service id
     */
    private function captureDsnFor(string $manager): string
    {
        $dsn = null;

        $this->container->bind(
            PDO::class,
            static function (ContainerContract $container, array $arguments) use (&$dsn): PDO {
                $dsn = $arguments[0] ?? null;

                return new PdoFixture('sqlite::memory:');
            }
        );

        $callback = new OrmServiceProvider()->publishers()[$manager];
        $callback($this->container);

        self::assertIsString($dsn);

        return $dsn;
    }
}
