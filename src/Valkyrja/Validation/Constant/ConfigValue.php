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

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Validation\Rule\Base;
use Valkyrja\Validation\Rule\ORM;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const RULE      = CKP::DEFAULT;
    public const RULES_MAP = [
        Rule::REQUIRED     => Base::class,
        Rule::EQUALS       => Base::class,
        Rule::EMPTY        => Base::class,
        Rule::NOT_EMPTY    => Base::class,
        Rule::ALPHA        => Base::class,
        Rule::LOWERCASE    => Base::class,
        Rule::UPPERCASE    => Base::class,
        Rule::MIN          => Base::class,
        Rule::MAX          => Base::class,
        Rule::STARTS_WITH  => Base::class,
        Rule::ENDS_WITH    => Base::class,
        Rule::CONTAINS     => Base::class,
        Rule::CONTAINS_ANY => Base::class,
        Rule::EMAIL        => Base::class,
        Rule::NUMERIC      => Base::class,
        Rule::BOOLEAN      => Base::class,
        Rule::LESS_THAN    => Base::class,
        Rule::GREATER_THAN => Base::class,
        Rule::ONE_OF       => Base::class,
        Rule::REGEX        => Base::class,
        Rule::ORM_UNIQUE   => ORM::class,
        Rule::ORM_EXISTS   => ORM::class,
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::RULE      => self::RULE,
        CKP::RULES_MAP => self::RULES_MAP,
    ];
}
