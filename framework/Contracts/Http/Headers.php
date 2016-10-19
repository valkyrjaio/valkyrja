<?php

namespace Valkyrja\Contracts\Http;

use Valkyrja\Contracts\Support\Collection;

interface Headers extends Collection
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
}
