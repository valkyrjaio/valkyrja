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

namespace Valkyrja\Log\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONFIGURATION = 'LOG_DEFAULT_CONFIGURATION';
    public const string CONFIGURATIONS        = 'LOG_CONFIGURATIONS';

    public const string PSR_ADAPTER_CLASS = 'LOG_PSR_ADAPTER_CLASS';
    public const string PSR_DRIVER_CLASS  = 'LOG_PSR_DRIVER_CLASS';
    public const string PSR_NAME          = 'LOG_PSR_NAME';
    public const string PSR_FILE_PATH     = 'LOG_PSR_FILE_PATH';

    public const string NULL_ADAPTER_CLASS = 'LOG_NULL_ADAPTER_CLASS';
    public const string NULL_DRIVER_CLASS  = 'LOG_NULL_DRIVER_CLASS';
}
