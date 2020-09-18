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

namespace Valkyrja\SMS\Facades;

use Valkyrja\SMS\Driver;
use Valkyrja\SMS\SMS as Contract;
use Valkyrja\SMS\Message;
use Valkyrja\Support\Facade\Facade;

/**
 * Class SMS.
 *
 * @author Melech Mizrachi
 *
 * @method static Driver useMessenger(string $name = null, string $adapter = null)
 * @method static Message createMessage(string $name = null)
 * @method static void send(Message $message)
 */
class SMS extends Facade
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
