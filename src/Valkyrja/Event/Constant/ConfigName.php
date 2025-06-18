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

namespace Valkyrja\Event\Constant;

/**
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const string LISTENER_CLASSES = 'listenerClasses';
    public const string FILE_PATH        = 'filePath';
    public const string CACHE_FILE_PATH  = 'cacheFilePath';
    public const string USE_CACHE        = 'useCache';
}
