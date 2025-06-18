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
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONFIGURATION         = 'BROADCAST_DEFAULT_CONFIGURATION';
    public const string CONFIGURATIONS                = 'BROADCAST_CONFIGURATIONS';
    public const string DEFAULT_MESSAGE_CONFIGURATION = 'BROADCAST_DEFAULT_MESSAGE_CONFIGURATION';
    public const string MESSAGE_CONFIGURATIONS        = 'BROADCAST_MESSAGE_CONFIGURATIONS';

    public const string PUSHER_ADAPTER_CLASS = 'BROADCAST_PUSHER_ADAPTER_CLASS';
    public const string PUSHER_DRIVER_CLASS  = 'BROADCAST_PUSHER_DRIVER_CLASS';
    public const string PUSHER_KEY           = 'BROADCAST_PUSHER_KEY';
    public const string PUSHER_SECRET        = 'BROADCAST_PUSHER_SECRET';
    public const string PUSHER_ID            = 'BROADCAST_PUSHER_ID';
    public const string PUSHER_CLUSTER       = 'BROADCAST_PUSHER_CLUSTER';
    public const string PUSHER_USE_TLS       = 'BROADCAST_PUSHER_USE_TLS';

    public const string LOG_ADAPTER_CLASS = 'BROADCAST_LOG_ADAPTER_CLASS';
    public const string LOG_DRIVER_CLASS  = 'BROADCAST_LOG_DRIVER_CLASS';
    public const string LOG_LOGGER        = 'BROADCAST_LOG_LOGGER';

    public const string NULL_ADAPTER_CLASS = 'BROADCAST_NULL_ADAPTER_CLASS';
    public const string NULL_DRIVER_CLASS  = 'BROADCAST_NULL_DRIVER_CLASS';

    public const string DEFAULT_MESSAGE_CHANNEL = 'BROADCAST_DEFAULT_MESSAGE_CHANNEL';
    public const string DEFAULT_MESSAGE_CLASS   = 'BROADCAST_DEFAULT_MESSAGE_CLASS';
}
