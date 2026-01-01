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

namespace Valkyrja\Mail\Mailer\Contract;

use Valkyrja\Mail\Data\Contract\MessageContract;

/**
 * Interface MailerContract.
 *
 * @author Melech Mizrachi
 */
interface MailerContract
{
    /**
     * Send a message.
     *
     * @param MessageContract $message The message to send
     *
     * @return void
     */
    public function send(MessageContract $message): void;
}
