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

namespace Valkyrja\Http\Message\Constant;

/**
 * Constant ContentType.
 *
 * @see    https://www.iana.org/assignments/media-types/media-types.xhtml
 */
final class ContentType
{
    public const string APPLICATION_JSON       = 'application/json';
    public const string APPLICATION_JAVASCRIPT = 'application/javascript';
    public const string APPLICATION_XML        = 'application/xml';
    public const string APPLICATION_XML_UTF8   = self::APPLICATION_XML . '; charset=utf-8';
    public const string APPLICATION_X_WWW_FORM = 'application/x-www-form-urlencoded';
    public const string MULTIPART_FORM_DATA    = 'multipart/form-data';
    public const string TEXT_HTML              = 'text/html';
    public const string TEXT_HTML_UTF8         = self::TEXT_HTML . '; charset=utf-8';
    public const string TEXT_JAVASCRIPT        = 'text/javascript';
    public const string TEXT_PLAIN             = 'text/plain';
    public const string TEXT_PLAIN_UTF8        = self::TEXT_PLAIN . '; charset=utf-8';
}
