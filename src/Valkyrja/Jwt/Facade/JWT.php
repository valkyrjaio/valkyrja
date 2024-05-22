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

namespace Valkyrja\Jwt\Facade;

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Jwt\Contract\Jwt as Contract;
use Valkyrja\Jwt\Driver\Contract\Driver;

/**
 * Class JWT.
 *
 * @author Melech Mizrachi
 *
 * @method static Driver useAlgo(string $algo = null, string $adapter = null)
 * @method static string encode(array $payload)
 * @method static array  decode(string $jwt)
 */
class JWT extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
