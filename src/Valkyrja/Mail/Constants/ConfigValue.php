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

namespace Valkyrja\Mail\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Mail\Messages\PHPMailerMessage;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const HOST         = 'smtp1.example.com;smtp2.example.com';
    public const PORT         = 587;
    public const FROM_ADDRESS = 'hello@example.com';
    public const FROM_NAME    = 'Example';
    public const ENCRYPTION   = 'tls';
    public const USERNAME     = '';
    public const PASSWORD     = '';
    public const MESSAGE      = CKP::PHP_MAILER;
    public const MESSAGES     = [
        CKP::PHP_MAILER => PHPMailerMessage::class,
    ];

    public static array $defaults = [
        CKP::HOST         => self::HOST,
        CKP::PORT         => self::PORT,
        CKP::FROM_ADDRESS => self::FROM_ADDRESS,
        CKP::FROM_NAME    => self::FROM_NAME,
        CKP::ENCRYPTION   => self::ENCRYPTION,
        CKP::USERNAME     => self::USERNAME,
        CKP::PASSWORD     => self::PASSWORD,
        CKP::MESSAGE      => self::MESSAGE,
        CKP::MESSAGES     => self::MESSAGES,
    ];
}