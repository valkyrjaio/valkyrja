<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Facade\Facades\Facade;
use Valkyrja\ORM\Adapter as AdapterContract;
use Valkyrja\ORM\Connection as ConnectionContract;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\PDOConnection;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query as QueryContract;
use Valkyrja\ORM\QueryBuilder as QueryBuilderContract;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\SoftDeleteEntity;

/**
 * Class EntityManager.
 *
 * @author Melech Mizrachi
 *
 * @method static AdapterContract getAdapter(string $name = null)
 * @method static ConnectionContract|PDOConnection getConnection(string $connection = null)
 * @method static QueryBuilderContract createQueryBuilder(string $entity = null, string $alias = null)
 * @method static QueryContract createQuery(string $query = null, string $entity = null)
 * @method static Repository getRepository(string $entity)
 * @method static Retriever createRetriever()
 * @method static Persister getPersister()
 * @method static bool beginTransaction()
 * @method static bool inTransaction()
 * @method static void ensureTransaction()
 * @method static bool persist()
 * @method static bool rollback()
 * @method static string lastInsertId()
 * @method static Retriever find(string $entity, bool $getRelations = false)
 * @method static Retriever findOne(string $entity, string|int $id, bool $getRelations = false)
 * @method static Retriever count(string $entity)
 * @method static void create(Entity $entity, bool $defer = true)
 * @method static void save(Entity $entity, bool $defer = true)
 * @method static void delete(Entity $entity, bool $defer = true)
 * @method static void softDelete(SoftDeleteEntity $entity, bool $defer = true)
 * @method static void clear(Entity $entity = null)
 */
class EntityManager extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->entityManager();
    }
}
