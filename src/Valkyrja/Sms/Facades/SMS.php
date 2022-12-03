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

namespace Valkyrja\Sms\Facades;

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Sms\Driver;
use Valkyrja\Sms\Message;
use Valkyrja\Sms\Sms as Contract;

/**
 * Class SMS.
 *
 * @author Melech Mizrachi
 *
 * @method static Driver useMessenger(string $name = null, string $adapter = null)
 * @method static Message createMessage(string $name = null)
 * @method static void send(Message $message)
 */
class SMS extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::$container->getSingleton(Contract::class);
    }
}
