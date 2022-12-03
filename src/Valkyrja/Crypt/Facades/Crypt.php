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

namespace Valkyrja\Crypt\Facades;

use Valkyrja\Crypt\Adapter;
use Valkyrja\Crypt\Crypt as Contract;
use Valkyrja\Facade\ContainerFacade;

/**
 * Class Crypt.
 *
 * @author Melech Mizrachi
 *
 * @method static Adapter useCrypt(string $name = null)
 * @method static string encrypt(string $message, string $key = null)
 * @method static string decrypt(string $encrypted, string $key = null)
 * @method static string encryptArray(array $array, string $key = null)
 * @method static array decryptArray(string $encrypted, string $key = null)
 * @method static string encryptObject(object $object, string $key = null)
 * @method static object decryptObject(string $encrypted, string $key = null)
 */
class Crypt extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
