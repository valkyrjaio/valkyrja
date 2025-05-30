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

namespace Valkyrja\Mail\Contract;

use Valkyrja\Mail\Adapter\Contract\Adapter;
use Valkyrja\Mail\Driver\Contract\Driver;
use Valkyrja\Mail\Factory\Contract\Factory;
use Valkyrja\Mail\Message\Contract\Message;
use Valkyrja\Manager\Contract\MessageManager as Manager;

/**
 * Interface Mail.
 *
 * @author Melech Mizrachi
 *
 * @extends Manager<Adapter, Driver, Factory, Message>
 */
interface Mail extends Manager
{
    /**
     * @inheritDoc
     *
     * @return Driver
     */
    public function use(string|null $name = null): Driver;

    /**
     * @inheritDoc
     *
     * @return Message
     */
    public function createMessage(string|null $name = null, array $data = []): Message;

    /**
     * Send a message.
     *
     * @param Message $message The message to send
     *
     * @return void
     */
    public function send(Message $message): void;
}
