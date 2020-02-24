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

namespace Valkyrja\Client\Facades;

use Psr\Http\Message\ResponseInterface;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Client.
 *
 * @author Melech Mizrachi
 *
 * @method static ResponseInterface request(string $method, string $uri, array $options = [])
 * @method static ResponseInterface get(string $uri, array $options = [])
 * @method static ResponseInterface post(string $uri, array $options = [])
 * @method static ResponseInterface head(string $uri, array $options = [])
 * @method static ResponseInterface put(string $uri, array $options = [])
 * @method static ResponseInterface patch(string $uri, array $options = [])
 * @method static ResponseInterface delete(string $uri, array $options = [])
 */
class Client extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->client();
    }
}
