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

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Override;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Data\Contract\MessageContract;
use Valkyrja\Type\Array\Factory\ArrayFactory;

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

        if ($data !== []) {
            $message = $message->withMessage(ArrayFactory::toString($data));
        }

        return $message;
    }
}
