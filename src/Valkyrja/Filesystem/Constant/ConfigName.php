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

namespace Valkyrja\Filesystem\Constant;

/**
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const DEFAULT_CONFIGURATION = 'defaultConfiguration';
    public const CONFIGURATIONS        = 'configurations';

    public const ADAPTER_CLASS = 'adapterClass';
    public const DRIVER_CLASS  = 'driverClass';

    public const FLYSYSTEM_ADAPTER = 'flysystemAdapter';
    public const DIR               = 'dir';
    public const KEY               = 'key';
    public const SECRET            = 'secret';
    public const REGION            = 'region';
    public const VERSION           = 'version';
    public const BUCKET            = 'bucket';
    public const PREFIX            = 'prefix';
    public const OPTIONS           = 'options';
}
