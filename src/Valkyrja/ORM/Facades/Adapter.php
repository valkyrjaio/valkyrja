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
use Valkyrja\Http\Enums\FacadeStaticMethod;
use Valkyrja\ORM\Adapter as Contract;
use Valkyrja\ORM\Connection as ConnectionContract;
use Valkyrja\ORM\PDOConnection;

/**
 * Class Adapter.
 *
 * @author Melech Mizrachi
 *
 * @method static Contract make(array $config)
 * @method static ConnectionContract|PDOConnection getConnection(string $connection = null)
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
        return Valkyrja::app()->orm()->getAdapter();
    }

    /**
     * Get an array of static methods.
     *
     * @return array
     */
    protected static function getStaticMethods(): array
    {
        return [
            FacadeStaticMethod::MAKE => FacadeStaticMethod::MAKE,
        ];
    }
}
