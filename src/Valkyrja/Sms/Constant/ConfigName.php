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

namespace Valkyrja\Sms\Constant;

/**
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const DEFAULT_CONFIGURATION         = 'defaultConfiguration';
    public const DEFAULT_MESSAGE_CONFIGURATION = 'defaultMessageConfiguration';
    public const CONFIGURATIONS                = 'configurations';
    public const MESSAGE_CONFIGURATIONS        = 'messageConfigurations';

    public const ADAPTER_CLASS = 'adapterClass';
    public const DRIVER_CLASS  = 'driverClass';
    public const MESSAGE_CLASS = 'messageClass';
    public const FROM          = 'from';
}
