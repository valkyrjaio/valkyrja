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

namespace Valkyrja\Broadcast\Adapter;

use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;
use Valkyrja\Broadcast\Adapter\Contract\PusherAdapter as Contract;
use Valkyrja\Broadcast\Message\Contract\Message;
use Valkyrja\Type\BuiltIn\Support\Arr;

/**
 * Class PusherAdapter.
 *
 * @author Melech Mizrachi
 */
class PusherAdapter extends NullAdapter implements Contract
{
    /**
     * The pusher service.
     *
     * @var Pusher
     */
    protected Pusher $pusher;

    /**
     * PusherAdapter constructor.
     *
     * @param Pusher $pusher The pusher service
     */
    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     * @throws GuzzleException
     * @throws ApiErrorException
     * @throws PusherException
     */
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
