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
