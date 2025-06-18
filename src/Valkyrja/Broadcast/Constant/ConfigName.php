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

namespace Valkyrja\Broadcast\Constant;

/**
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const string DEFAULT_CONFIGURATION         = 'defaultConfiguration';
    public const string CONFIGURATIONS                = 'configurations';
    public const string DEFAULT_MESSAGE_CONFIGURATION = 'defaultMessageConfiguration';
    public const string MESSAGE_CONFIGURATIONS        = 'messageConfigurations';

    public const string ADAPTER_CLASS = 'adapterClass';
    public const string DRIVER_CLASS  = 'driverClass';
    public const string LOGGER        = 'logger';
    public const string KEY           = 'key';
    public const string SECRET        = 'secret';
    public const string ID            = 'id';
    public const string CLUSTER       = 'cluster';
    public const string USE_TLS       = 'useTls';
    public const string MESSAGE_CLASS = 'messageClass';
    public const string CHANNEL       = 'channel';
}
