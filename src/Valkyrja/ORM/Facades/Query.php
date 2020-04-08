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

use Valkyrja\Facade\Facades\Facade;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\Query as Contract;

use function Valkyrja\orm;

/**
 * Class Query.
 *
 * @author Melech Mizrachi
 *
 * @method static Contract table(string $table)
 * @method static Contract entity(string $entity)
 * @method static Contract prepare(string $query)
 * @method static Contract bindValue(string $column, $property)
 * @method static Contract execute()
 * @method static Entity[]|object[] getResult()
 * @method static Entity|null getOneOrNull()
 * @method static Entity getOneOrFail()
 * @method static int getCount()
 * @method static string getError()
 */
class Query extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return orm()->createQuery();
    }
}
