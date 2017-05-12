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
 * Class Server.
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
    public function getHeaders(): array
    {
        $headers        = [];
        $specialHeaders = self::SPECIAL_HEADERS;

        foreach ($this->collection as $key => $value) {
            if (in_array($key, $specialHeaders, true) || 0 === strpos($key, 'HTTP_')) {
                $headers[$this->getHeaderName($key)] = $value;
            }
        }

        return $headers;
    }

    /**
     * Get the correct HTTP header name.
     *
     * @param string $header
     *
     * @return string
     */
    protected function getHeaderName($header): string
    {
        if (0 === strpos($header, 'HTTP_')) {
            $header = substr($header, 5);
        }

        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower($header))));

        return $header;
    }
}
