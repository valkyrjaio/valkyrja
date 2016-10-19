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

namespace Valkyrja\Contracts\Http;

use Valkyrja\Contracts\Support\Collection;

/**
 * Interface Server
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author Melech Mizrachi
 */
interface Server extends Collection
{
    /**
     * Special HTTP headers that do not have the "HTTP_" prefix
     *
     * @var array
     */
    const SPECIAL_HEADERS = [
        'CONTENT_TYPE',
        'CONTENT_LENGTH',
        'PHP_AUTH_USER',
        'PHP_AUTH_PW',
        'PHP_AUTH_DIGEST',
        'AUTH_TYPE',
    ];

    /**
     * Get all headers from server.
     *
     * @return array
     */
    public function getHeaders();
}
