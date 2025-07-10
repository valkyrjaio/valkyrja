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

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Override;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;
use Valkyrja\Broadcast\Contract\Broadcaster as Contract;
use Valkyrja\Broadcast\Data\Contract\Message;
use Valkyrja\Type\BuiltIn\Support\Arr;

/**
 * Class PusherBroadcaster.
 *
 * @author Melech Mizrachi
 */
class PusherBroadcaster implements Contract
{
    /**
     * PusherBroadcaster constructor.
     *
     * @param Pusher $pusher The pusher service
     */
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
    public function send(Message $message): void
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
     * @param Message $message The message
     *
     * @throws JsonException On json decode failure
     *
     * @return string
     */
    protected function getMessageText(Message $message): string
    {
        $this->prepareMessage($message);

        return $message->getMessage();
    }

    /**
     * Prepare a message that has data.
     *
     * @param Message $message The message
     *
     * @throws JsonException On json decode failure
     *
     * @return void
     */
    protected function prepareMessage(Message $message): void
    {
        $data = $message->getData();

        if ($data !== null && $data !== []) {
            $message->setMessage(Arr::toString($data));
        }
    }
}
