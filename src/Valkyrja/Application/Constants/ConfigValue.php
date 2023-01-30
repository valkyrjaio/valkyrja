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

namespace Valkyrja\Application\Constants;

use Valkyrja\Application\Application;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Container\Providers\AppProvider as ContainerAppProvider;
use Valkyrja\Exception\Classes\ExceptionHandler;
use Valkyrja\HttpKernel\Kernels\Kernel;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const ENV               = 'production';
    public const DEBUG             = false;
    public const URL               = 'localhost';
    public const TIMEZONE          = 'UTC';
    public const VERSION           = Application::VERSION;
    public const KEY               = 'some_secret_app_key';
    public const EXCEPTION_HANDLER = ExceptionHandler::class;
    public const HTTP_KERNEL       = Kernel::class;
    public const PROVIDERS         = [
        ContainerAppProvider::class,
    ];

    public static array $defaults = [
        CKP::ENV               => self::ENV,
        CKP::DEBUG             => self::DEBUG,
        CKP::URL               => self::URL,
        CKP::TIMEZONE          => self::TIMEZONE,
        CKP::VERSION           => self::VERSION,
        CKP::KEY               => self::KEY,
        CKP::EXCEPTION_HANDLER => self::EXCEPTION_HANDLER,
        CKP::HTTP_KERNEL       => self::HTTP_KERNEL,
        CKP::PROVIDERS         => self::PROVIDERS,
    ];
}
