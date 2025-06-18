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
    public const string DEFAULT_CONFIGURATION = 'defaultConfiguration';
    public const string CONFIGURATIONS        = 'configurations';

    public const string ADAPTER_CLASS = 'adapterClass';
    public const string DRIVER_CLASS  = 'driverClass';

    public const string FLYSYSTEM_ADAPTER = 'flysystemAdapter';
    public const string DIR               = 'dir';
    public const string KEY               = 'key';
    public const string SECRET            = 'secret';
    public const string REGION            = 'region';
    public const string VERSION           = 'version';
    public const string BUCKET            = 'bucket';
    public const string PREFIX            = 'prefix';
    public const string OPTIONS           = 'options';
}
