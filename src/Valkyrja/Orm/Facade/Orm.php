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

namespace Valkyrja\Orm\Facade;

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Orm\Adapter\Contract\Adapter;
use Valkyrja\Orm\Contract\Orm as Contract;
use Valkyrja\Orm\Driver\Contract\Driver;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Entity\Contract\SoftDeleteEntity;
use Valkyrja\Orm\Persister\Contract\Persister;
use Valkyrja\Orm\Query\Contract\Query;
use Valkyrja\Orm\QueryBuilder\Contract\QueryBuilder;
use Valkyrja\Orm\Repository\Contract\Repository;
use Valkyrja\Orm\Retriever\Contract\Retriever;
use Valkyrja\Orm\Statement\Contract\Statement;

/**
 * Class ORM.
 *
 * @author Melech Mizrachi
 *
 * @method static Driver       useConnection(string $name = null, string $adapter = null)
 * @method static Adapter      createAdapter(string $name, array $config)
 * @method static QueryBuilder createQueryBuilder(Adapter $adapter, string $name)
 * @method static Query        createQuery(Adapter $adapter, string $name)
 * @method static Retriever    createRetriever(Adapter $adapter, string $name)
 * @method static Persister    createPersister(Adapter $adapter, string $name)
 * @method static Repository   getRepository(string $entity)
 * @method static Repository   getRepositoryFromClass(Entity $entity)
 * @method static Statement    createStatement(Adapter $adapter, string $name, array $data = [])
 * @method static bool         beginTransaction()
 * @method static bool         inTransaction()
 * @method static void         ensureTransaction()
 * @method static bool         persist()
 * @method static bool         rollback()
 * @method static string       lastInsertId()
 * @method static Retriever    find(string $entity, bool $getRelations = false)
 * @method static Retriever    findOne(string $entity, int|string $id, bool $getRelations = false)
 * @method static Retriever    count(string $entity)
 * @method static void         create(Entity $entity, bool $defer = true)
 * @method static void         save(Entity $entity, bool $defer = true)
 * @method static void         delete(Entity $entity, bool $defer = true)
 * @method static void         softDelete(SoftDeleteEntity $entity, bool $defer = true)
 * @method static void         clear(Entity $entity = null)
 */
class Orm extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
