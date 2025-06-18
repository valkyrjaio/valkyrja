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

namespace Valkyrja\Console\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string HANDLERS           = 'CONSOLE_HANDLERS';
    public const string PROVIDERS          = 'CONSOLE_PROVIDERS';
    public const string DEV_PROVIDERS      = 'CONSOLE_DEV_PROVIDERS';
    public const string SHOULD_RUN_QUIETLY = 'CONSOLE_SHOULD_RUN_QUIETLY';
    public const string FILE_PATH          = 'CONSOLE_FILE_PATH';
    public const string CACHE_FILE_PATH    = 'CONSOLE_CACHE_FILE_PATH';
    public const string SHOULD_USE_CACHE   = 'CONSOLE_SHOULD_USE_CACHE';
}
