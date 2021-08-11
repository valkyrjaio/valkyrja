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

namespace Valkyrja\Routing\Constants;

/**
 * Constant Regex.
 *
 * @author Melech Mizrachi
 */
final class Regex
{
    public const NUM                        = '\d+';
    public const ID                         = self::NUM;
    public const SLUG                       = '[a-zA-Z0-9-]+';
    public const UUID                       = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';
    public const ALPHA                      = '[a-zA-Z]+';
    public const ALPHA_LOWERCASE            = '[a-z]+';
    public const ALPHA_UPPERCASE            = '[A-Z]+';
    public const ALPHA_NUM                  = '[a-zA-Z0-9]+';
    public const ALPHA_NUM_UNDERSCORE       = '\w+';
    public const START                      = '/^';
    public const END                        = '$/';
    public const START_CAPTURE_GROUP        = '(';
    public const START_NON_CAPTURE_GROUP    = '(?:';
    public const END_CAPTURE_GROUP          = ')';
    public const END_OPTIONAL_CAPTURE_GROUP = ')?';
}
