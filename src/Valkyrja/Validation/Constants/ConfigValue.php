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

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Validation\Rules\Base;
use Valkyrja\Validation\Rules\ORM;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const RULE      = CKP::DEFAULT;
    public const RULES     = [
        CKP::DEFAULT => Base::class,
        CKP::ORM     => ORM::class,
    ];
    public const RULES_MAP = [
        Rule::REQUIRED     => CKP::DEFAULT,
        Rule::EQUALS       => CKP::DEFAULT,
        Rule::EMPTY        => CKP::DEFAULT,
        Rule::NOT_EMPTY    => CKP::DEFAULT,
        Rule::MIN          => CKP::DEFAULT,
        Rule::MAX          => CKP::DEFAULT,
        Rule::STARTS_WITH  => CKP::DEFAULT,
        Rule::ENDS_WITH    => CKP::DEFAULT,
        Rule::CONTAINS     => CKP::DEFAULT,
        Rule::CONTAINS_ANY => CKP::DEFAULT,
        Rule::EMAIL        => CKP::DEFAULT,
        Rule::NUMERIC      => CKP::DEFAULT,
        Rule::LESS_THAN    => CKP::DEFAULT,
        Rule::GREATER_THAN => CKP::DEFAULT,
        Rule::ORM_UNIQUE   => CKP::ORM,
        Rule::ORM_EXISTS   => CKP::ORM,
    ];

    public static array $defaults = [
        CKP::RULE      => self::RULE,
        CKP::RULES     => self::RULES,
        CKP::RULES_MAP => self::RULES_MAP,
    ];
}
