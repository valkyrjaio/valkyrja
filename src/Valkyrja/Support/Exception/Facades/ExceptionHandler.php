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

namespace Valkyrja\Support\Exception\Facades;

use Throwable;
use Valkyrja\Support\Exception\ExceptionHandler as Contract;
use Valkyrja\Support\Facade\Facade;

use const E_ALL;

/**
 * Class ExceptionHandler.
 *
 * @author Melech Mizrachi
 *
 * @method static void enable(int $errorReportingLevel = E_ALL, bool $displayErrors = false)
 * @method static string getTraceCode(Throwable $exception)
 */
class ExceptionHandler extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return self::$container->getSingleton(Contract::class);
    }
}
