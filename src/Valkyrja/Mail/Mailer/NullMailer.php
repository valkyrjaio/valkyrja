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

namespace Valkyrja\Mail\Mailer;

use Override;
use Valkyrja\Mail\Data\Contract\MessageContract;
use Valkyrja\Mail\Mailer\Contract\MailerContract as Contract;

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
    #[Override]
    public function send(MessageContract $message): void
    {
    }
}
