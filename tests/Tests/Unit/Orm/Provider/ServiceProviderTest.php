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

use PDO;
use PHPUnit\Framework\MockObject\Exception;
use Valkyrja\Application\Env\Env;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Orm\Entity\Abstract\Entity;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Manager\MysqlManager;
use Valkyrja\Orm\Manager\NullManager;
use Valkyrja\Orm\Manager\PgsqlManager;
use Valkyrja\Orm\Manager\SqliteManager;
use Valkyrja\Orm\Provider\OrmServiceProvider;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\PhpUnit\Abstract\ServiceProviderTestCase;
use Valkyrja\Tests\Fixtures\Env\OrmMysqlStrictEngineEnvFixture;
use Valkyrja\Tests\Fixtures\Orm\PdoFixture;

/**
 * Test the ServiceProvider.
 */
final class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = OrmServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ManagerContract::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(MysqlManager::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(PgsqlManager::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(SqliteManager::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(PDO::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(NullManager::class, new OrmServiceProvider()->publishers());
        self::assertArrayHasKey(Repository::class, new OrmServiceProvider()->publishers());
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
        $this->container->setSingleton(Env::class, new OrmMysqlStrictEngineEnvFixture());

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
        $callback = new OrmServiceProvider()->publishers()[NullManager::class];
        $callback($this->container);

        self::assertInstanceOf(NullManager::class, $this->container->getSingleton(NullManager::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishRepository(): void
    {
        $manager = self::createStub(MysqlManager::class);
        $entity  = Entity::class;

        $callback = new OrmServiceProvider()->publishers()[Repository::class];
        $callback($this->container);

        self::assertInstanceOf(Repository::class, $this->container->getService(Repository::class, [$manager, $entity]));
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
