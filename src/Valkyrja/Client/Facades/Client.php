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

namespace Valkyrja\Client\Facades;

use Valkyrja\Client\Client as Contract;
use Valkyrja\Client\Driver;
use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Http\Response;

/**
 * Class Client.
 *
 * @author Melech Mizrachi
 *
 * @method static Driver   useClient(string $name = null, string $adapter = null)
 * @method static Response request(string $method, string $uri, array $options = [])
 * @method static Response get(string $uri, array $options = [])
 * @method static Response post(string $uri, array $options = [])
 * @method static Response head(string $uri, array $options = [])
 * @method static Response put(string $uri, array $options = [])
 * @method static Response patch(string $uri, array $options = [])
 * @method static Response delete(string $uri, array $options = [])
 */
class Client extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
