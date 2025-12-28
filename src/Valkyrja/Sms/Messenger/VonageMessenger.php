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
use Valkyrja\Sms\Data\Contract\Message;
use Valkyrja\Sms\Messenger\Contract\Messenger as Contract;
use Vonage\Client as Vonage;
use Vonage\Client\Exception\Exception as ClientException;
use Vonage\SMS\Message\SMS;

/**
 * Class VonageSms.
 *
 * @author Melech Mizrachi
 */
class VonageMessenger implements Contract
{
    /**
     * VonageSms constructor.
     */
    public function __construct(
        protected Vonage $vonage
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ClientExceptionInterface
     * @throws ClientException
     */
    #[Override]
    public function send(Message $message): void
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
