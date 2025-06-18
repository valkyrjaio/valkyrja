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
    public const string ENV           = 'APP_ENV';
    public const string DEBUG         = 'APP_DEBUG';
    public const string URL           = 'APP_URL';
    public const string TIMEZONE      = 'APP_TIMEZONE';
    public const string VERSION       = 'APP_VERSION';
    public const string KEY           = 'APP_KEY';
    public const string ERROR_HANDLER = 'APP_ERROR_HANDLER';
    public const string PROVIDERS     = 'APP_PROVIDERS';
}
