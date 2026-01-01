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

namespace Valkyrja\Broadcast\Broadcaster;

use Override;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract as Contract;
use Valkyrja\Broadcast\Data\Contract\MessageContract;

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
    #[Override]
    public function send(MessageContract $message): void
    {
    }
}
