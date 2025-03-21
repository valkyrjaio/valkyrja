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

namespace Valkyrja\Container\Constant;

/**
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const ALIASES          = 'aliases';
    public const SERVICES         = 'services';
    public const CONTEXT_SERVICES = 'contextServices';
    public const PROVIDERS        = 'providers';
    public const DEV_PROVIDERS    = 'devProviders';
    public const USE_ATTRIBUTES   = 'useAttributes';
    public const FILE_PATH        = 'filePath';
    public const CACHE_FILE_PATH  = 'cacheFilePath';
    public const USE_CACHE        = 'useCache';
}
