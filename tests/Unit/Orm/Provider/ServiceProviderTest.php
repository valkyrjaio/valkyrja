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
use Valkyrja\Container\Contract\Container;
use Valkyrja\Orm\Contract\Manager as Contract;
use Valkyrja\Orm\Entity\Entity;
use Valkyrja\Orm\InMemoryManager;
use Valkyrja\Orm\MysqlManager;
use Valkyrja\Orm\NullManager;
use Valkyrja\Orm\PgsqlManager;
use Valkyrja\Orm\Provider\ServiceProvider;
use Valkyrja\Orm\Repository\Repository;
use Valkyrja\Orm\SqliteManager;
use Valkyrja\Tests\Classes\Orm\PdoClass;
use Valkyrja\Tests\Unit\Container\Provider\ServiceProviderTestCase;

/**
 * Test the ServiceProvider.
 *
 * @author Melech Mizrachi
 */
class ServiceProviderTest extends ServiceProviderTestCase
{
    /** @inheritDoc */
    protected static string $provider = ServiceProvider::class;

    /**
     * @throws Exception
     */
    public function testPublishManager(): void
    {
        $this->container->setSingleton(MysqlManager::class, self::createStub(MysqlManager::class));

        ServiceProvider::publishManager($this->container);

        self::assertInstanceOf(MysqlManager::class, $this->container->getSingleton(Contract::class));
    }

    public function testPublishMysqlManager(): void
    {
        $this->container->setCallable(PDO::class, static fn (Container $container, string $dsn, array $options): PDO => new PdoClass('sqlite::memory:'));

        ServiceProvider::publishMysqlManager($this->container);

        self::assertInstanceOf(MysqlManager::class, $this->container->getSingleton(MysqlManager::class));
    }

    public function testPublishPgsqlManager(): void
    {
        $this->container->setCallable(PDO::class, static fn (Container $container, string $dsn, array $options): PDO => new PdoClass('sqlite::memory:'));

        ServiceProvider::publishPgsqlManager($this->container);

        self::assertInstanceOf(PgsqlManager::class, $this->container->getSingleton(PgsqlManager::class));
    }

    public function testPublishSqliteManager(): void
    {
        ServiceProvider::publishPdo($this->container);
        ServiceProvider::publishSqliteManager($this->container);

        self::assertInstanceOf(SqliteManager::class, $this->container->getSingleton(SqliteManager::class));
    }

    public function testPublishInMemoryManager(): void
    {
        ServiceProvider::publishInMemoryManager($this->container);

        self::assertInstanceOf(InMemoryManager::class, $this->container->getSingleton(InMemoryManager::class));
    }

    public function testPublishNullManager(): void
    {
        ServiceProvider::publishNullManager($this->container);

        self::assertInstanceOf(NullManager::class, $this->container->getSingleton(NullManager::class));
    }

    /**
     * @throws Exception
     */
    public function testPublishRepository(): void
    {
        $manager = self::createStub(MysqlManager::class);
        $entity  = Entity::class;

        ServiceProvider::publishRepository($this->container);

        self::assertInstanceOf(Repository::class, $this->container->getCallable(Repository::class, [$manager, $entity]));
    }

    /**
     * @throws Exception
     */
    public function testPublishPdo(): void
    {
        $dsn     = 'sqlite::memory:';
        $options = [];

        ServiceProvider::publishPdo($this->container);

        self::assertInstanceOf(PDO::class, $this->container->getCallable(PDO::class, [$dsn, $options]));
    }
}
