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

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Override;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Data\Contract\MessageContract;
use Valkyrja\Type\BuiltIn\Support\Arr;

class PusherBroadcaster implements BroadcasterContract
{
    public function __construct(
        protected Pusher $pusher
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     * @throws GuzzleException
     * @throws ApiErrorException
     * @throws PusherException
     */
    #[Override]
    public function send(MessageContract $message): void
    {
        $this->pusher->trigger(
            $message->getChannel(),
            $message->getEvent(),
            $this->getMessageText($message)
        );
    }

    /**
     * Get the message text.
     *
     * @param MessageContract $message The message
     *
     * @throws JsonException On json decode failure
     */
    protected function getMessageText(MessageContract $message): string
    {
        return $this->prepareMessage($message)
            ->getMessage();
    }

    /**
     * Prepare a message that has data.
     *
     * @param MessageContract $message The message
     *
     * @throws JsonException On json decode failure
     */
    protected function prepareMessage(MessageContract $message): MessageContract
    {
        $data = $message->getData();

        if ($data !== null && $data !== []) {
            $message = $message->withMessage(Arr::toString($data));
        }

        return $message;
    }
}
