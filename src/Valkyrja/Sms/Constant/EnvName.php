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
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONFIGURATION         = 'SMS_DEFAULT_CONFIGURATION';
    public const string CONFIGURATIONS                = 'SMS_CONFIGURATIONS';
    public const string DEFAULT_MESSAGE_CONFIGURATION = 'SMS_DEFAULT_MESSAGE_CONFIGURATION';
    public const string MESSAGE_CONFIGURATIONS        = 'SMS_MESSAGE_CONFIGURATIONS';

    public const string VONAGE_KEY           = 'SMS_VONAGE_KEY';
    public const string VONAGE_SECRET        = 'SMS_VONAGE_SECRET';
    public const string VONAGE_ADAPTER_CLASS = 'SMS_VONAGE_ADAPTER_CLASS';
    public const string VONAGE_DRIVER_CLASS  = 'SMS_VONAGE_DRIVER_CLASS';

    public const string LOG_ADAPTER_CLASS = 'SMS_LOG_ADAPTER_CLASS';
    public const string LOG_DRIVER_CLASS  = 'SMS_LOG_DRIVER_CLASS';
    public const string LOG_LOG_NAME      = 'SMS_LOG_LOG_NAME';

    public const string NULL_ADAPTER_CLASS = 'SMS_NULL_ADAPTER_CLASS';
    public const string NULL_DRIVER_CLASS  = 'SMS_NULL_DRIVER_CLASS';

    public const string DEFAULT_MESSAGE_FROM  = 'SMS_DEFAULT_MESSAGE_FROM';
    public const string DEFAULT_MESSAGE_CLASS = 'SMS_DEFAULT_MESSAGE_CLASS';
}
