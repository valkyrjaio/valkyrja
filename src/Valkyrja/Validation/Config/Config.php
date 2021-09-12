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
        CKP::RULES     => EnvKey::VALIDATION_RULES,
        CKP::RULES_MAP => EnvKey::VALIDATION_RULES_MAP,
    ];

    /**
     * The default rule.
     *
     * @var string
     */
    public string $rule;

    /**
     * The rules.
     *
     * @var string[]
     */
    public array $rules;

    /**
     * The rules map.
     *
     * @var string[]
     */
    public array $rulesMap;
}
