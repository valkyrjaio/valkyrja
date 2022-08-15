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

namespace Valkyrja\SMS;

use Valkyrja\Support\Manager\MessageManager;

/**
 * Interface SMS.
 *
 * @author Melech Mizrachi
 */
interface SMS extends MessageManager
{
    /**
     * @inheritDoc
     *
     * @return Driver
     */
    public function use(string $name = null): Driver;

    /**
     * @inheritDoc
     *
     * @return Message
     */
    public function createMessage(string $name = null, array $data = []): Message;

    /**
     * Send a message.
     *
     * @param Message $message The message to send
     *
     * @return void
     */
    public function send(Message $message): void;
}
