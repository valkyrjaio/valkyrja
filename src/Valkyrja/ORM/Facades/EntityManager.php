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
use Valkyrja\ORM\Adapter;
use Valkyrja\ORM\Connection;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\PDOConnection;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;

/**
 * Class EntityManager.
 *
 * @author Melech Mizrachi
 *
 * @method static Adapter getAdapter(string $name = null)
 * @method static Connection|PDOConnection getConnection(string $connection = null)
 * @method static QueryBuilder createQueryBuilder(string $entity = null, string $alias = null)
 * @method static Query createQuery(string $query = null, string $entity = null)
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
