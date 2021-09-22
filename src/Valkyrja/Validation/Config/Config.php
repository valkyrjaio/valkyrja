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

namespace Valkyrja\Validation\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Validation\Constants\Rule;
use Valkyrja\Validation\Rules\Base;
use Valkyrja\Validation\Rules\ORM;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     */
    protected static array $envKeys = [
        CKP::RULE      => EnvKey::VALIDATION_RULE,
        CKP::RULES_MAP => EnvKey::VALIDATION_RULES_MAP,
    ];

    /**
     * The default rule.
     *
     * @var string
     */
    public string $rule = Base::class;

    /**
     * The rules map.
     *
     * @var string[]
     */
    public array $rulesMap = [
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
        Rule::ORM_UNIQUE   => ORM::class,
        Rule::ORM_EXISTS   => ORM::class,
    ];
}
