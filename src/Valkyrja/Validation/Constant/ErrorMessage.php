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

namespace Valkyrja\Validation\Constant;

class ErrorMessage
{
    /** @var non-empty-string */
    public const string REQUIRED = 'This field is required.';
    /** @var non-empty-string */
    public const string INT_GREATER_THAN = 'This field value is too low.';
    /** @var non-empty-string */
    public const string INT_LESS_THAN = 'This field value is too high.';
    /** @var non-empty-string */
    public const string IS_EMAIL = 'This field must be a valid email.';
    /** @var non-empty-string */
    public const string IS_EQUAL = 'This field must be the same.';
    /** @var non-empty-string */
    public const string IS_BOOL = 'This field must be a boolean.';
    /** @var non-empty-string */
    public const string IS_EMPTY = 'This field must be empty.';
    /** @var non-empty-string */
    public const string IS_NUMERIC = 'This field must be numeric.';
    /** @var non-empty-string */
    public const string IS_STRING = 'This field must be a string.';
    /** @var non-empty-string */
    public const string IS_NOT_EMPTY = 'This field must not be empty.';
    /** @var non-empty-string */
    public const string IS_NOT_EQUAL = 'This field must not be the same.';
    /** @var non-empty-string */
    public const string ENTITY_EXISTS = 'This field must match an existing entity.';
    /** @var non-empty-string */
    public const string ENTITY_NOT_EXISTS = 'This field must not match an existing entity.';
    /** @var non-empty-string */
    public const string STRING_ALPHA = 'This field must be alphanumeric.';
    /** @var non-empty-string */
    public const string STRING_CONTAINS = 'This field must contain another string.';
    /** @var non-empty-string */
    public const string STRING_ENDS_WITH = 'This field must end with another string.';
    /** @var non-empty-string */
    public const string STRING_LOWERCASE = 'This field must be lowercase.';
    /** @var non-empty-string */
    public const string STRING_MAX = 'This field is too long.';
    /** @var non-empty-string */
    public const string STRING_MIN = 'This field is too short.';
    /** @var non-empty-string */
    public const string STRING_REGEX = 'This field must match the regex.';
    /** @var non-empty-string */
    public const string STRING_STARTS_WITH = 'This field must start with another string.';
    /** @var non-empty-string */
    public const string STRING_UPPERCASE = 'This field must be uppercase.';
}
