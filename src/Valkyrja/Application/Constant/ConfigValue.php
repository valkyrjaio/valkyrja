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

namespace Valkyrja\Application\Constant;

use Valkyrja\Application\Contract\Application;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Container\Provider\AppProvider as ContainerAppProvider;
use Valkyrja\Exception\ErrorHandler;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const ENV           = 'production';
    public const DEBUG         = false;
    public const URL           = 'localhost';
    public const TIMEZONE      = 'UTC';
    public const VERSION       = Application::VERSION;
    public const KEY           = 'some_secret_app_key';
    public const ERROR_HANDLER = ErrorHandler::class;
    public const PROVIDERS     = [
        ContainerAppProvider::class,
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::ENV           => self::ENV,
        CKP::DEBUG         => self::DEBUG,
        CKP::URL           => self::URL,
        CKP::TIMEZONE      => self::TIMEZONE,
        CKP::VERSION       => self::VERSION,
        CKP::KEY           => self::KEY,
        CKP::ERROR_HANDLER => self::ERROR_HANDLER,
        CKP::PROVIDERS     => self::PROVIDERS,
    ];
}
