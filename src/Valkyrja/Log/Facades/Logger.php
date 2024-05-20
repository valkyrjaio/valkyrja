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

namespace Valkyrja\Log\Facades;

use Throwable;
use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Log\Driver;
use Valkyrja\Log\Logger as Contract;

/**
 * Class Logger.
 *
 * @author Melech Mizrachi
 *
 * @method static Driver useLogger(string $name = null, string $adapter = null)
 * @method static void   debug(string $message, array $context = [])
 * @method static void   info(string $message, array $context = [])
 * @method static void   notice(string $message, array $context = [])
 * @method static void   warning(string $message, array $context = [])
 * @method static void   error(string $message, array $context = [])
 * @method static void   critical(string $message, array $context = [])
 * @method static void   alert(string $message, array $context = [])
 * @method static void   emergency(string $message, array $context = [])
 * @method static void   log(string $message, array $context = [])
 * @method static void   exception(Throwable $exception, string $message, array $context = [])
 */
class Logger extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
