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
    public const ADAPTER  = CKP::NEXMO;
    public const ADAPTERS = [
        CKP::LOG   => [
            CKP::DRIVER  => LogAdapter::class,
            CKP::ADAPTER => null,
        ],
        CKP::NEXMO => [
            CKP::DRIVER   => NexmoAdapter::class,
            CKP::USERNAME => '',
            CKP::PASSWORD => '',
        ],
        CKP::NULL  => [
            CKP::DRIVER => NullAdapter::class,
        ],
    ];
    public const MESSAGE  = CKP::DEFAULT;
    public const MESSAGES = [
        CKP::DEFAULT => Message::class,
    ];

    public static array $defaults = [
        CKP::ADAPTER  => self::ADAPTER,
        CKP::ADAPTERS => self::ADAPTERS,
        CKP::MESSAGE  => self::MESSAGE,
        CKP::MESSAGES => self::MESSAGES,
    ];
}
