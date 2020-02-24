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
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;

/**
 * Class EntityManager.
 *
 * @author Melech Mizrachi
 *
 * @method static QueryBuilder queryBuilder(string $entity = null, string $alias = null)
 * @method static Query query(string $query, string $entity = null)
 * @method static Repository repository(string $entity)
 * @method static bool beginTransaction()
 * @method static bool commit()
 * @method static bool rollback()
 * @method static string lastInsertId()
 * @method static Entity|null find(string $entity, bool $useRepository, $id, bool $getRelations = null)
 * @method static Entity|null findBy(string $entity, bool $useRepository, array $criteria, array $orderBy = null, int $offset = null, array $columns = null, bool $getRelations = null)
 * @method static Entity[] findAll(string $entity, bool $useRepository, array $orderBy = null, array $columns = null, bool $getRelations = null)
 * @method static Entity[] findAllBy(string $entity, bool $useRepository, array $criteria, array $orderBy = null, int $limit = null, int $offset = null, array $columns = null, bool $getRelations = null)
 * @method static int count(string $entity, bool $useRepository, array $criteria)
 * @method static void create(Entity $entity, bool $useRepository)
 * @method static void save(Entity $entity, bool $useRepository)
 * @method static void delete(Entity $entity, bool $useRepository)
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
