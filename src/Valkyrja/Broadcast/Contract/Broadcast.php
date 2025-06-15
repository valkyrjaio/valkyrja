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

namespace Valkyrja\Broadcast\Contract;

use Valkyrja\Broadcast\Driver\Contract\Driver;
use Valkyrja\Broadcast\Message\Contract\Message;

/**
 * Interface Broadcast.
 *
 * @author Melech Mizrachi
 */
interface Broadcast
{
    /**
     * Use a specific configuration.
     */
    public function use(string|null $name = null): Driver;

    /**
     * Create a new message.
     *
     * @param array<array-key, mixed> $data [optional] The data
     */
    public function createMessage(string|null $name = null, array $data = []): Message;
}
