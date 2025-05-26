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

namespace Valkyrja\Session\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const DEFAULT_CONFIGURATION = 'SESSION_DEFAULT_CONFIGURATION';

    public const PHP_ADAPTER_CLASS = 'SESSION_PHP_ADAPTER_CLASS';
    public const PHP_DRIVER_CLASS  = 'SESSION_PHP_DRIVER_CLASS';
    public const PHP_ID            = 'SESSION_PHP_ID';
    public const PHP_NAME          = 'SESSION_PHP_NAME';
}
