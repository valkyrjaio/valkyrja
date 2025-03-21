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

namespace Valkyrja\Config\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const CLASS                 = 'CONFIG_CLASS';
    public const PROVIDERS             = 'CONFIG_PROVIDERS';
    public const FILE_PATH             = 'CONFIG_FILE_PATH';
    public const CACHE_FILE_PATH       = 'CONFIG_CACHE_FILE_PATH';
    public const CACHE_ALLOWED_CLASSES = 'CONFIG_CACHE_ALLOWED_CLASSES';
    public const USE_CACHE             = 'CONFIG_USE_CACHE';
}
