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

namespace Valkyrja\Mail;

use Valkyrja\Mail\Contract\Mailer as Contract;
use Valkyrja\Mail\Data\Contract\Message;

/**
 * Class NullMailer.
 *
 * @author Melech Mizrachi
 */
class NullMailer implements Contract
{
    /**
     * @inheritDoc
     */
    public function send(Message $message): void
    {
    }
}
