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

namespace Valkyrja\Notification\Factory\Contract;

use Valkyrja\Notification\Data\Contract\Notify;

/**
 * Interface Factory.
 *
 * @author Melech Mizrachi
 */
interface Factory
{
    /**
     * Create a new notification.
     *
     * @param string                  $name The notification name
     * @param array<array-key, mixed> $data [optional] The data to add to the notification
     *
     * @return Notify
     */
    public function createNotification(string $name, array $data = []): Notify;
}
