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
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Orm\Entity\Abstract\Entity;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Manager\InMemoryManager;
use Valkyrja\Orm\Manager\MysqlManager;
use Valkyrja\Orm\Manager\NullManager;
use Valkyrja\Orm\Manager\PgsqlManager;
use Valkyrja\Orm\Manager\SqliteManager;
use Valkyrja\Orm\Provider\ServiceProvider;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Tests\Classes\Orm\PdoClass;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    public function testExpectedPublishers(): void
    {
        self::assertArrayHasKey(ManagerContract::class, ServiceProvider::publishers());
        self::assertArrayHasKey(MysqlManager::class, ServiceProvider::publishers());
        self::assertArrayHasKey(PgsqlManager::class, ServiceProvider::publishers());
        self::assertArrayHasKey(SqliteManager::class, ServiceProvider::publishers());
        self::assertArrayHasKey(PDO::class, ServiceProvider::publishers());
        self::assertArrayHasKey(InMemoryManager::class, ServiceProvider::publishers());
        self::assertArrayHasKey(NullManager::class, ServiceProvider::publishers());
        self::assertArrayHasKey(Repository::class, ServiceProvider::publishers());
    }

    public function testExpectedProvides(): void
    {
        self::assertContains(ManagerContract::class, ServiceProvider::provides());
        self::assertContains(MysqlManager::class, ServiceProvider::provides());
        self::assertContains(PgsqlManager::class, ServiceProvider::provides());
        self::assertContains(SqliteManager::class, ServiceProvider::provides());
        self::assertContains(PDO::class, ServiceProvider::provides());
        self::assertContains(InMemoryManager::class, ServiceProvider::provides());
        self::assertContains(NullManager::class, ServiceProvider::provides());
        self::assertContains(Repository::class, ServiceProvider::provides());
    }

    /**
     * @throws Exception
     */
    public function testPublishManager(): void
    {
        $this->container->setSingleton(MysqlManager::class, self::createStub(MysqlManager::class));

        $callback = ServiceProvider::publishers()[ManagerContract::class];
        $callback($this->container);

        self::assertInstanceOf(MysqlManager::class, $this->container->getSingleton(ManagerContract::class));
    }

    public function testPublishMysqlManager(): void
    {
        $this->container->setCallable(
            PDO::class,
            static fn (ContainerContract $container, string $dsn, array $options): PDO => new PdoClass('sqlite::memory:')
        );

        $callback = ServiceProvider::publishers()[MysqlManager::class];
        $callback($this->container);

        self::assertInstanceOf(MysqlManager::class, $this->container->getSingleton(MysqlManager::class));
    }

    public function testPublishPgsqlManager(): void
    {
        $this->container->setCallable(
            PDO::class,
            static fn (ContainerContract $container, string $dsn, array $options): PDO => new PdoClass('sqlite::memory:')
        );

        $callback = ServiceProvider::publishers()[PgsqlManager::class];
        $callback($this->container);

        self::assertInstanceOf(PgsqlManager::class, $this->container->getSingleton(PgsqlManager::class));
    }

    public function testPublishSqliteManager(): void
    {
        $this->container->setCallable(
            PDO::class,
            static fn (ContainerContract $container, string $dsn, array $options): PDO => new PdoClass('sqlite::memory:')
        );

        $callback = ServiceProvider::publishers()[SqliteManager::class];
        $callback($this->container);

        self::assertInstanceOf(SqliteManager::class, $this->container->getSingleton(SqliteManager::class));
    }

    public function testPublishInMemoryManager(): void
    {
        $callback = ServiceProvider::publishers()[InMemoryManager::class];
        $callback($this->container);

        self::assertInstanceOf(InMemoryManager::class, $this->container->getSingleton(InMemoryManager::class));
    }

    public function testPublishNullManager(): void
    {
        $callback = ServiceProvider::publishers()[NullManager::class];
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

        $callback = ServiceProvider::publishers()[Repository::class];
        $callback($this->container);

        self::assertInstanceOf(Repository::class, $this->container->getCallable(Repository::class, [$manager, $entity]));
    }

    /**
     * @throws Exception
     */
    public function testPublishPdo(): void
    {
        $dsn     = 'sqlite::memory:';
        $options = [];

        $callback = ServiceProvider::publishers()[PDO::class];
        $callback($this->container);

        self::assertInstanceOf(PDO::class, $this->container->getCallable(PDO::class, [$dsn, $options]));
    }
}
