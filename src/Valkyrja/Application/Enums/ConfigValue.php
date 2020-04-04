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

namespace Valkyrja\Application\Enums;

use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKeyPart as CKP;
use Valkyrja\Container\Dispatchers\Container;
use Valkyrja\Dispatcher\Dispatchers\Dispatcher;
use Valkyrja\Event\Dispatchers\Events;
use Valkyrja\Exception\Handlers\ExceptionHandler;
use Valkyrja\Http\Exceptions\HttpException;

/**
 * Enum ConfigValue.
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
    public const CONTAINER         = Container::class;
    public const DISPATCHER        = Dispatcher::class;
    public const EVENTS            = Events::class;
    public const EXCEPTION_HANDLER = ExceptionHandler::class;
    public const HTTP_EXCEPTION    = HttpException::class;

    public static array $defaults = [
        CKP::ENV               => self::ENV,
        CKP::DEBUG             => self::DEBUG,
        CKP::URL               => self::URL,
        CKP::TIMEZONE          => self::TIMEZONE,
        CKP::VERSION           => self::VERSION,
        CKP::KEY               => self::KEY,
        CKP::CONTAINER         => self::CONTAINER,
        CKP::DISPATCHER        => self::DISPATCHER,
        CKP::EVENTS            => self::EVENTS,
        CKP::EXCEPTION_HANDLER => self::EXCEPTION_HANDLER,
        CKP::HTTP_EXCEPTION    => self::HTTP_EXCEPTION,
    ];
}
