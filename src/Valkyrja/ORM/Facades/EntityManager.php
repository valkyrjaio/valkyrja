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
use Valkyrja\ORM\Query as QueryContract;
use Valkyrja\ORM\QueryBuilder as QueryBuilderContract;
use Valkyrja\ORM\Repository;

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
 * @method static bool beginTransaction()
 * @method static bool inTransaction()
 * @method static void ensureTransaction()
 * @method static bool commit()
 * @method static bool rollback()
 * @method static string lastInsertId()
 * @method static Entity|null find(string $entity, $id, bool $getRelations = false)
 * @method static Entity|null findBy(string $entity, array $criteria, array $orderBy = null, int $offset = null, array $columns = null, bool $getRelations = false)
 * @method static Entity[] findAll(string $entity, array $orderBy = null, array $columns = null, bool $getRelations = false)
 * @method static Entity[] findAllBy(string $entity, array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $columns = null, bool $getRelations = false)
 * @method static int count(string $entity, array $criteria)
 * @method static void create(Entity $entity)
 * @method static void save(Entity $entity)
 * @method static void delete(Entity $entity)
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
