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
use Valkyrja\Broadcast\Data\Contract\Message;
use Valkyrja\Crypt\Manager\Contract\Crypt;
use Valkyrja\Crypt\Throwable\Exception\CryptException;

/**
 * Class CryptPusherBroadcaster.
 *
 * @author Melech Mizrachi
 */
class CryptPusherBroadcaster extends PusherBroadcaster
{
    /**
     * CryptPusherBroadcaster constructor.
     */
    public function __construct(
        Pusher $pusher,
        protected Crypt $crypt
    ) {
        parent::__construct($pusher);
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException On a crypt failure
     */
    #[Override]
    protected function prepareMessage(Message $message): void
    {
        parent::prepareMessage($message);

        $message->setMessage(
            $this->crypt->encrypt($message->getMessage())
        );
    }
}
