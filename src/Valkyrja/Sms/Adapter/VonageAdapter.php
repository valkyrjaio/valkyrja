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

namespace Valkyrja\Sms\Adapter;

use Exception;
use Psr\Http\Client\ClientExceptionInterface;
use Valkyrja\Sms\Adapter\Contract\VonageAdapter as Contract;
use Valkyrja\Sms\Message\Contract\Message;
use Vonage\Client as Vonage;
use Vonage\SMS\Message\SMS;

/**
 * Class VonageAdapter.
 *
 * @author Melech Mizrachi
 */
class VonageAdapter implements Contract
{
    /**
     * The Nexmo client.
     *
     * @var Vonage
     */
    protected Vonage $vonage;

    /**
     * VonageMessage constructor.
     *
     * @param Vonage $vonage The Vonage client
     */
    public function __construct(Vonage $vonage)
    {
        $this->vonage = $vonage;
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
