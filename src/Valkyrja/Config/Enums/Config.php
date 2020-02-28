<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Enums;

use Valkyrja\Config\Configs\AnnotationConfig;
use Valkyrja\Config\Configs\AppConfig;
use Valkyrja\Config\Configs\CacheConfig;
use Valkyrja\Config\Configs\ConsoleConfig;
use Valkyrja\Config\Configs\ContainerConfig;
use Valkyrja\Config\Configs\CryptConfig;
use Valkyrja\Config\Configs\EventConfig;
use Valkyrja\Config\Configs\Filesystem\DisksConfig;
use Valkyrja\Config\Configs\Filesystem\LocalConfig;
use Valkyrja\Config\Configs\Filesystem\S3Config;
use Valkyrja\Config\Configs\FilesystemConfig;
use Valkyrja\Config\Configs\LoggingConfig;
use Valkyrja\Config\Configs\MailConfig;
use Valkyrja\Config\Configs\ORM\ConnectionConfig;
use Valkyrja\Config\Configs\ORM\ConnectionsConfig;
use Valkyrja\Config\Configs\ORMConfig;
use Valkyrja\Config\Configs\PathConfig;
use Valkyrja\Config\Configs\RoutingConfig;
use Valkyrja\Config\Configs\SessionConfig;
use Valkyrja\Config\Configs\ViewConfig;
use Valkyrja\Config\Models\AnnotatableConfig;
use Valkyrja\Config\Models\CacheableConfig;
use Valkyrja\Config\Models\ConfigModel;
use Valkyrja\Enum\Enums\Enum;

/**
 * Enum Config.
 *
 * @author Melech Mizrachi
 */
final class Config extends Enum
{
    public const ALLOWED_CLASSES = [
        \Valkyrja\Config\Config::class,
        AnnotatableConfig::class,
        CacheableConfig::class,
        ConfigModel::class,
        AnnotationConfig::class,
        AppConfig::class,
        CacheConfig::class,
        ConsoleConfig::class,
        ContainerConfig::class,
        CryptConfig::class,
        EventConfig::class,
        FilesystemConfig::class,
        LoggingConfig::class,
        MailConfig::class,
        ORMConfig::class,
        PathConfig::class,
        RoutingConfig::class,
        SessionConfig::class,
        ViewConfig::class,
        ConnectionsConfig::class,
        ConnectionConfig::class,
        DisksConfig::class,
        LocalConfig::class,
        S3Config::class,
    ];
}
