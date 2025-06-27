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
    public const string ENV             = 'APP_ENV';
    public const string DEBUG_MODE      = 'APP_DEBUG_MODE';
    public const string URL             = 'APP_URL';
    public const string TIMEZONE        = 'APP_TIMEZONE';
    public const string VERSION         = 'APP_VERSION';
    public const string KEY             = 'APP_KEY';
    public const string COMPONENTS      = 'APP_COMPONENTS';
    public const string CACHE_FILE_PATH = 'APP_CACHE_FILE_PATH';
}
