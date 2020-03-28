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

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Facade\Facades\Facade;
use Valkyrja\ORM\Persister;
use Valkyrja\ORM\Query as QueryContract;
use Valkyrja\ORM\QueryBuilder as QueryBuilderContract;
use Valkyrja\ORM\Retriever;
use Valkyrja\ORM\Statement;

/**
 * Class Connection.
 *
 * @author Melech Mizrachi
 *
 * @method static bool beginTransaction()
 * @method static bool inTransaction()
 * @method static void ensureTransaction()
 * @method static bool commit()
 * @method static bool rollback()
 * @method static string lastInsertId()
 * @method static Statement prepare(string $query)
 * @method static QueryContract createQuery(string $query = null, string $entity = null)
 * @method static QueryBuilderContract createQueryBuilder(string $entity = null, string $alias = null)
 * @method static Retriever createRetriever()
 * @method static Persister getPersister()
 */
class Connection extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->orm()->getConnection();
    }
}
