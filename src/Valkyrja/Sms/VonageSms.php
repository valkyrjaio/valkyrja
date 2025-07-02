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

namespace Valkyrja\Sms;

use Exception;
use Psr\Http\Client\ClientExceptionInterface;
use Valkyrja\Sms\Contract\Sms as Contract;
use Valkyrja\Sms\Data\Contract\Message;
use Vonage\Client as Vonage;
use Vonage\SMS\Message\SMS;

/**
 * Class VonageSms.
 *
 * @author Melech Mizrachi
 */
class VonageSms implements Contract
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
     * @throws Exception
     * @throws ClientExceptionInterface
     */
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
