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
use Pusher\Pusher;
use Valkyrja\Broadcast\Data\Contract\MessageContract;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Throwable\Exception\CryptException;

class CryptPusherBroadcaster extends PusherBroadcaster
{
    public function __construct(
        Pusher $pusher,
        protected CryptContract $crypt
    ) {
        parent::__construct($pusher);
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException On a crypt failure
     */
    #[Override]
    protected function prepareMessage(MessageContract $message): MessageContract
    {
        $message = parent::prepareMessage($message);

        /** @var non-empty-string $encrypted */
        $encrypted = $this->crypt->encrypt($message->getMessage());

        return $message->withMessage($encrypted);
    }
}
