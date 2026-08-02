<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Broadcast\Broadcaster;

use Override;
use Pusher\Pusher;
use Valkyrja\Broadcast\Data\Contract\MessageContract;
use Valkyrja\Crypt\Manager\Contract\CryptContract;

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
