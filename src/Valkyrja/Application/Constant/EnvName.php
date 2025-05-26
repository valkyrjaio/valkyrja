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

namespace Valkyrja\Application\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const ENV           = 'APP_ENV';
    public const DEBUG         = 'APP_DEBUG';
    public const URL           = 'APP_URL';
    public const TIMEZONE      = 'APP_TIMEZONE';
    public const VERSION       = 'APP_VERSION';
    public const KEY           = 'APP_KEY';
    public const ERROR_HANDLER = 'APP_ERROR_HANDLER';
    public const PROVIDERS     = 'APP_PROVIDERS';
}
