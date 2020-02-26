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
use Valkyrja\ORM\Query as QueryContract;
use Valkyrja\ORM\QueryBuilder as Contract;

/**
 * Class QueryBuilder.
 *
 * @author Melech Mizrachi
 *
 * @method static Contract select(array $columns = null)
 * @method static Contract insert()
 * @method static Contract update()
 * @method static Contract delete()
 * @method static Contract table(string $table, string $alias = null)
 * @method static Contract entity(string $entity, string $alias = null)
 * @method static Contract set(string $column, string $value)
 * @method static Contract where(string $where)
 * @method static Contract andWhere(string $where)
 * @method static Contract orWhere(string $where)
 * @method static Contract orderBy(string $column)
 * @method static Contract orderByAsc(string $column)
 * @method static Contract orderByDesc(string $column)
 * @method static Contract limit(int $limit)
 * @method static Contract offset(int $offset)
 * @method static string getQueryString()
 * @method static QueryContract getQuery()
 */
class QueryBuilder extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->entityManager()->queryBuilder();
    }
}
