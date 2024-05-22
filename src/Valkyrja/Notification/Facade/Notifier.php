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

namespace Valkyrja\Notification\Facade;

use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Notification\Contract\Notification as Contract;
use Valkyrja\Notification\Data\Contract\Notify;
use Valkyrja\Notification\Entity\Contract\NotifiableUser;

/**
 * Class Notifier.
 *
 * @author Melech Mizrachi
 *
 * @method static Contract addMailRecipient(string $email, string $name = '')
 * @method static Contract addSmsRecipient(string $phoneNumber)
 * @method static Contract addUserRecipient(NotifiableUser $user)
 * @method static void     notify(Notify $notification)
 * @method static void     notifyUser(Notify $notification, NotifiableUser $user)
 * @method static void     notifyUsers(Notify $notification, NotifiableUser ...$users)
 */
class Notifier extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
