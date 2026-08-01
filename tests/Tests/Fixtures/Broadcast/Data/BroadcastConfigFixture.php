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

namespace Valkyrja\Tests\Fixtures\Broadcast\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Broadcast\Broadcaster\Contract\BroadcasterContract;
use Valkyrja\Broadcast\Broadcaster\NullBroadcaster;
use Valkyrja\Broadcast\Data\BroadcastLogConfig;
use Valkyrja\Broadcast\Data\BroadcastPusherConfig;
use Valkyrja\Broadcast\Data\Contract\BroadcastConfigContract;

final class BroadcastConfigFixture extends Config implements BroadcastConfigContract
{
    /**
     * @param class-string<BroadcasterContract> $defaultBroadcaster
     */
    public function __construct(
        public string $defaultBroadcaster = NullBroadcaster::class,
        public BroadcastPusherConfig $pusher = new BroadcastPusherConfig(),
        public BroadcastLogConfig $log = new BroadcastLogConfig(),
    ) {
        parent::__construct();
    }
}
