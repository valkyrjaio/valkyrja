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
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONFIGURATION = 'FILESYSTEM_DEFAULT_CONFIGURATION';
    public const string CONFIGURATIONS        = 'FILESYSTEM_CONFIGURATIONS';

    public const string FLYSYSTEM_LOCAL_ADAPTER_CLASS     = 'FILESYSTEM_FLYSYSTEM_LOCAL_ADAPTER_CLASS';
    public const string FLYSYSTEM_LOCAL_DRIVER_CLASS      = 'FILESYSTEM_FLYSYSTEM_LOCAL_DRIVER_CLASS';
    public const string FLYSYSTEM_LOCAL_FLYSYSTEM_ADAPTER = 'FILESYSTEM_FLYSYSTEM_LOCAL_FLYSYSTEM_ADAPTER';
    public const string FLYSYSTEM_LOCAL_DIR               = 'FILESYSTEM_FLYSYSTEM_LOCAL_DIR';

    public const string IN_MEMORY_ADAPTER_CLASS = 'FILESYSTEM_IN_MEMORY_ADAPTER_CLASS';
    public const string IN_MEMORY_DRIVER_CLASS  = 'FILESYSTEM_IN_MEMORY_DRIVER_CLASS';
    public const string IN_MEMORY_DIR           = 'FILESYSTEM_IN_MEMORY_DIR';

    public const string FLYSYSTEM_S3_ADAPTER_CLASS     = 'FILESYSTEM_FLYSYSTEM_S3_ADAPTER_CLASS';
    public const string FLYSYSTEM_S3_DRIVER_CLASS      = 'FILESYSTEM_FLYSYSTEM_S3_DRIVER_CLASS';
    public const string FLYSYSTEM_S3_FLYSYSTEM_ADAPTER = 'FILESYSTEM_FLYSYSTEM_S3_FLYSYSTEM_ADAPTER';
    public const string FLYSYSTEM_S3_KEY               = 'FILESYSTEM_FLYSYSTEM_S3_KEY';
    public const string FLYSYSTEM_S3_SECRET            = 'FILESYSTEM_FLYSYSTEM_S3_SECRET';
    public const string FLYSYSTEM_S3_REGION            = 'FILESYSTEM_FLYSYSTEM_S3_REGION';
    public const string FLYSYSTEM_S3_VERSION           = 'FILESYSTEM_FLYSYSTEM_S3_VERSION';
    public const string FLYSYSTEM_S3_BUCKET            = 'FILESYSTEM_FLYSYSTEM_S3_BUCKET';
    public const string FLYSYSTEM_S3_PREFIX            = 'FILESYSTEM_FLYSYSTEM_S3_PREFIX';
    public const string FLYSYSTEM_S3_OPTIONS           = 'FILESYSTEM_FLYSYSTEM_S3_OPTIONS';

    public const string NULL_ADAPTER_CLASS = 'FILESYSTEM_NULL_ADAPTER_CLASS';
    public const string NULL_DRIVER_CLASS  = 'FILESYSTEM_NULL_DRIVER_CLASS';
}
