<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Http;

/**
 * Interface Request
 *
 * @package Valkyrja\Contracts\Http
 *
 * @author  Melech Mizrachi
 */
interface Request
{
    const HEADER_FORWARDED    = 'forwarded';
    const HEADER_CLIENT_IP    = 'client_ip';
    const HEADER_CLIENT_HOST  = 'client_host';
    const HEADER_CLIENT_PROTO = 'client_proto';
    const HEADER_CLIENT_PORT  = 'client_port';

    const METHOD_HEAD    = 'HEAD';
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_PATCH   = 'PATCH';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_PURGE   = 'PURGE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_TRACE   = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';

    // TODO: Implement
}
