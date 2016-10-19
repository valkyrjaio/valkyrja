<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based off work by Fabien Potencier for symfony/http-foundation/Request.php
 */

namespace Valkyrja\Http;

use Valkyrja\Contracts\Http\Server as ServerContract;
use Valkyrja\Support\Collection;

/**
 * Class Server
 *
 * @package Valkyrja\Http
 *
 * @author Melech Mizrachi
 */
class Server extends Collection implements ServerContract
{
    /**
     * Get all headers from server.
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = [];
        $specialHeaders = self::SPECIAL_HEADERS;

        foreach ($this->collection as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_' || isset($specialHeaders[$key])) {
                $headers[$key] = $value;
            }
        }

        return $headers;
    }
}
