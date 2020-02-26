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
use Valkyrja\ORM\Connection;
use Valkyrja\ORM\PDOConnection;
use Valkyrja\ORM\QueryBuilder;

/**
 * Class Adapter.
 *
 * @author Melech Mizrachi
 *
 * @method static Connection|PDOConnection getConnection(string $connection)
 * @method static QueryBuilder createQueryBuilder(string $entity = null, string $alias = null)
 */
class Adapter extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->entityManager()->getAdapter();
    }
}
