<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Sms\Messenger;

use Override;
use Valkyrja\Sms\Data\Contract\MessageContract;
use Valkyrja\Sms\Messenger\Contract\MessengerContract;

class NullMessenger implements MessengerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function send(MessageContract $message): void
    {
    }
}
