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

namespace Valkyrja\Broadcast;

use Valkyrja\Broadcast\Contract\Broadcaster as Contract;
use Valkyrja\Broadcast\Data\Contract\Message;

/**
 * Class NullBroadcaster.
 *
 * @author Melech Mizrachi
 */
class NullBroadcaster implements Contract
{
    /**
     * @inheritDoc
     */
    public function send(Message $message): void
    {
    }
}
