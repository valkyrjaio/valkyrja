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

namespace Valkyrja\SMS\Adapters;

use Valkyrja\SMS\Adapter;
use Valkyrja\SMS\Message;

/**
 * Class NullAdapter.
 *
 * @author Melech Mizrachi
 */
class NullAdapter implements Adapter
{
    /**
     * Send a message.
     *
     * @param Message $message The message to send
     *
     * @return void
     */
    public function send(Message $message): void
    {
    }
}
