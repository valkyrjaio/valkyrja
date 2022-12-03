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

namespace Valkyrja\Notification\Facades;

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Notification\NotifiableUser;
use Valkyrja\Notification\Notification;
use Valkyrja\Notification\Notifier as Contract;

/**
 * Class Notifier.
 *
 * @author Melech Mizrachi
 *
 * @method static Contract addMailRecipient(string $email, string $name = '')
 * @method static Contract addSmsRecipient(string $phoneNumber)
 * @method static Contract addUserRecipient(NotifiableUser $user)
 * @method static void notify(Notification $notification)
 * @method static void notifyUser(Notification $notification, NotifiableUser $user)
 * @method static void notifyUsers(Notification $notification, NotifiableUser ...$users)
 */
class Notifier extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::$container->getSingleton(Contract::class);
    }
}
