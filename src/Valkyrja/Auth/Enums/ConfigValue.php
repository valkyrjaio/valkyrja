<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Auth\Enums;

use Valkyrja\Auth\Adapters\Adapter;
use Valkyrja\Auth\Entities\User;
use Valkyrja\Auth\Repositories\Repository;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Enum\Enums\Enum;

/**
 * Enum Config.
 */
final class ConfigValue extends Enum
{
    public const ADAPTER                = CKP::DEFAULT;
    public const USER                   = User::class;
    public const REPOSITORY             = Repository::class;
    public const ALWAYS_AUTHENTICATE    = false;
    public const KEEP_USER_FRESH        = false;
    public const AUTHENTICATE_ROUTE     = RouteName::AUTHENTICATE;
    public const PASSWORD_CONFIRM_ROUTE = RouteName::PASSWORD_CONFIRM;
    public const ADAPTERS               = [
        CKP::DEFAULT => Adapter::class,
    ];
}
