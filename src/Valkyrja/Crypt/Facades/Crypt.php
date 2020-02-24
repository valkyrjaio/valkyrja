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

namespace Valkyrja\Crypt\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Crypt.
 *
 * @author Melech Mizrachi
 *
 * @method static string getKey()
 * @method static string encrypt(string $message, string $key = null)
 * @method static string decrypt(string $encrypted, string $key = null)
 * @method static string encryptArray(array $array, string $key = null)
 * @method static array decryptArray(string $encrypted, string $key = null)
 * @method static string encryptObject(object $object, string $key = null)
 * @method static object decryptObject(string $encrypted, string $key = null)
 */
class Crypt extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->crypt();
    }
}
