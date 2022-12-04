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

namespace Valkyrja\Exception\Facades;

use Throwable;
use Valkyrja\Exception\ExceptionHandler as Contract;
use Valkyrja\Facade\ContainerFacade;

use const E_ALL;

/**
 * Class ExceptionHandler.
 *
 * @author Melech Mizrachi
 *
 * @method static void enable(int $errorReportingLevel = E_ALL, bool $displayErrors = false)
 * @method static string getTraceCode(Throwable $exception)
 */
class ExceptionHandler extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
