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

namespace Valkyrja\ORM\Facades;

use Valkyrja\ORM\Driver;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\ORM as Contract;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query;
use Valkyrja\ORM\QueryBuilder;
use Valkyrja\ORM\Repository;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\SoftDeleteEntity;
use Valkyrja\Support\Facade\Facade;

/**
 * Class ORM.
 *
 * @author Melech Mizrachi
 *
 * @method static Driver useConnection(string $name = null, string $adapter = null)
 * @method static QueryBuilder createQueryBuilder(string $entity = null, string $alias = null)
 * @method static Query createQuery(string $query = null, string $entity = null)
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
class ORM extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return self::$container->getSingleton(Contract::class);
    }
}
