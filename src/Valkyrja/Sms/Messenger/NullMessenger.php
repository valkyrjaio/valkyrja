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
use Valkyrja\Sms\Data\Contract\MessageContract;
use Valkyrja\Sms\Messenger\Contract\MessengerContract as Contract;

/**
 * Class NullSms.
 */
class NullMessenger implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function send(MessageContract $message): void
    {
    }
}
