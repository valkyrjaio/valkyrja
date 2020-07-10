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

namespace Valkyrja\Validation\Constants;

/**
 * Constant Rule.
 *
 * @author Melech Mizrachi
 */
final class Rule
{
    public const REQUIRED     = 'required';
    public const EQUALS       = 'equals';
    public const EMPTY        = 'empty';
    public const NOT_EMPTY    = 'notEmpty';
    public const MIN          = 'min';
    public const MAX          = 'max';
    public const STARTS_WITH  = 'startsWith';
    public const ENDS_WITH    = 'endsWith';
    public const CONTAINS     = 'contains';
    public const EMAIL        = 'email';
    public const NUMERICAL    = 'numerical';
    public const LESS_THAN    = 'lessThan';
    public const GREATER_THAN = 'greaterThan';
    public const ORM_UNIQUE   = 'ORM:unique';
    public const ORM_EXISTS   = 'ORM:exists';
}
