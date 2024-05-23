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
 * @author Melech Mizrachi
 */
final class ContentType
{
    public const APPLICATION_JSON       = 'application/json';
    public const APPLICATION_JAVASCRIPT = 'application/javascript';
    public const TEXT_HTML              = 'text/html';
    public const TEXT_HTML_UTF8         = self::TEXT_HTML . '; charset=utf-8';
    public const TEXT_JAVASCRIPT        = 'text/javascript';
    public const TEXT_PLAIN             = 'text/plain';
    public const TEXT_PLAIN_UTF8        = self::TEXT_PLAIN . '; charset=utf-8';
}
