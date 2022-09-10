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

namespace Valkyrja\JWT\Facades;

use Valkyrja\JWT\Driver;
use Valkyrja\JWT\JWT as Contract;
use Valkyrja\Support\Facade\Facade;

/**
 * Class JWT.
 *
 * @author Melech Mizrachi
 *
 * @method static Driver useAlgo(string $algo = null, string $adapter = null)
 * @method static string encode(array $payload)
 * @method static array decode(string $jwt)
 */
class JWT extends Facade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::$container->getSingleton(Contract::class);
    }
}
