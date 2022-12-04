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

namespace Valkyrja\Orm\Facades;

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Orm\Adapter;
use Valkyrja\Orm\Driver;
use Valkyrja\Orm\Entity;
use Valkyrja\Orm\Orm as Contract;
use Valkyrja\Orm\Persister;
use Valkyrja\Orm\Query;
use Valkyrja\Orm\QueryBuilder;
use Valkyrja\Orm\Repository;
use Valkyrja\Orm\Retriever;
use Valkyrja\Orm\SoftDeleteEntity;
use Valkyrja\Orm\Statement;

/**
 * Class ORM.
 *
 * @author Melech Mizrachi
 *
 * @method static Driver useConnection(string $name = null, string $adapter = null)
 * @method static Adapter createAdapter(string $name, array $config)
 * @method static QueryBuilder createQueryBuilder(Adapter $adapter, string $name)
 * @method static Query createQuery(Adapter $adapter, string $name)
 * @method static Retriever createRetriever(Adapter $adapter, string $name)
 * @method static Persister createPersister(Adapter $adapter, string $name)
 * @method static Repository getRepository(string $entity)
 * @method static Repository getRepositoryFromClass(Entity $entity)
 * @method static Statement createStatement(Adapter $adapter, string $name, array $data = [])
 * @method static bool beginTransaction()
 * @method static bool inTransaction()
 * @method static void ensureTransaction()
 * @method static bool persist()
 * @method static bool rollback()
 * @method static string lastInsertId()
 * @method static Retriever find(string $entity, bool $getRelations = false)
 * @method static Retriever findOne(string $entity, int|string $id, bool $getRelations = false)
 * @method static Retriever count(string $entity)
 * @method static void create(Entity $entity, bool $defer = true)
 * @method static void save(Entity $entity, bool $defer = true)
 * @method static void delete(Entity $entity, bool $defer = true)
 * @method static void softDelete(SoftDeleteEntity $entity, bool $defer = true)
 * @method static void clear(Entity $entity = null)
 */
class Orm extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
