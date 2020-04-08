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

use Valkyrja\Facade\Facades\Facade;
use Valkyrja\Log\Logger as Contract;

/**
 * Class Logger.
 *
 * @author Melech Mizrachi
 *
 * @method static Contract debug(string $message, array $context = [])
 * @method static Contract info(string $message, array $context = [])
 * @method static Contract notice(string $message, array $context = [])
 * @method static Contract warning(string $message, array $context = [])
 * @method static Contract error(string $message, array $context = [])
 * @method static Contract critical(string $message, array $context = [])
 * @method static Contract alert(string $message, array $context = [])
 * @method static Contract emergency(string $message, array $context = [])
 * @method static Contract log(string $message, array $context = [])
 */
class Logger extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return \Valkyrja\logger();
    }
}
