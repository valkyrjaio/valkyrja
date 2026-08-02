<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Sms\Messenger;

use Override;
use Psr\Http\Client\ClientExceptionInterface;
use Valkyrja\Sms\Data\Contract\MessageContract;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;
use Vonage\Client;
use Vonage\Client\Exception\Exception;
use Vonage\SMS\Message\SMS;

class VonageMessenger implements MessengerContract
{
    public function __construct(
        protected Client $vonage
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    #[Override]
    public function send(MessageContract $message): void
    {
        $this->vonage->sms()->send(
            new SMS(
                to: $message->getTo(),
                from: $message->getFrom(),
                message: $message->getText(),
                type: $message->isUnicode() ? 'unicode' : 'text',
            )
        );
    }
}
