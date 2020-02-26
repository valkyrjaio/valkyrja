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

namespace Valkyrja\Session\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Session.
 *
 * @author Melech Mizrachi
 *
 * @method static void start()
 * @method static string id()
 * @method static void setId(string $id)
 * @method static string name()
 * @method static void setName(string $name)
 * @method static bool isActive()
 * @method static bool has(string $id)
 * @method static mixed get(string $id)
 * @method static void set(string $id, string $value)
 * @method static bool remove(string $id)
 * @method static string csrf(string $id)
 * @method static bool validateCsrf(string $id, string $token)
 * @method static void clear()
 * @method static void destroy()
 */
class Session extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->session();
    }
}