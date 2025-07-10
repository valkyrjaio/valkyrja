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

use Override;
use Valkyrja\Sms\Contract\Sms as Contract;
use Valkyrja\Sms\Data\Contract\Message;

/**
 * Class NullSms.
 *
 * @author Melech Mizrachi
 */
class NullSms implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function send(Message $message): void
    {
    }
}
