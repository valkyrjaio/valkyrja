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
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const LISTENER_CLASSES = 'EVENT_LISTENER_CLASSES';
    public const FILE_PATH        = 'EVENT_FILE_PATH';
    public const CACHE_FILE_PATH  = 'EVENT_CACHE_FILE_PATH';
    public const USE_CACHE        = 'EVENT_USE_CACHE_FILE';
}
