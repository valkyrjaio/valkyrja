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

namespace Valkyrja\SMS\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\SMS\Adapters\LogAdapter;
use Valkyrja\SMS\Adapters\NexmoAdapter;
use Valkyrja\SMS\Adapters\NullAdapter;
use Valkyrja\SMS\Messages\Message;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const USERNAME = '';
    public const PASSWORD = '';
    public const ADAPTER  = CKP::NEXMO;
    public const ADAPTERS = [
        CKP::LOG   => LogAdapter::class,
        CKP::NULL  => NullAdapter::class,
        CKP::NEXMO => NexmoAdapter::class,
    ];
    public const MESSAGE  = CKP::DEFAULT;
    public const MESSAGES = [
        CKP::DEFAULT => Message::class,
    ];

    public static array $defaults = [
        CKP::USERNAME => self::USERNAME,
        CKP::PASSWORD => self::PASSWORD,
        CKP::ADAPTER  => self::ADAPTER,
        CKP::ADAPTERS => self::ADAPTERS,
        CKP::MESSAGE  => self::MESSAGE,
        CKP::MESSAGES => self::MESSAGES,
    ];
}
