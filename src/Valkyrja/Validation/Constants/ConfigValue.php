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
    public const RULE  = CKP::DEFAULT;
    public const RULES = [
        CKP::DEFAULT => Base::class,
        CKP::ORM     => ORM::class,
    ];

    public static array $defaults = [
        CKP::RULE  => self::RULE,
        CKP::RULES => self::RULES,
    ];
}
