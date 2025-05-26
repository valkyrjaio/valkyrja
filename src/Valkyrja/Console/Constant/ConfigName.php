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
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const HANDLERS           = 'handlers';
    public const PROVIDERS          = 'providers';
    public const DEV_PROVIDERS      = 'devProviders';
    public const SHOULD_RUN_QUIETLY = 'shouldRunQuietly';
    public const FILE_PATH          = 'filePath';
    public const CACHE_FILE_PATH    = 'cacheFilePath';
    public const SHOULD_USE_CACHE   = 'shouldUseCache';
}
