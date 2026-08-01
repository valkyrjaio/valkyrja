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
use Valkyrja\Broadcast\Data\Contract\BroadcastConfigContract;
use Valkyrja\Broadcast\Data\Contract\BroadcastLogConfigContract;
use Valkyrja\Broadcast\Data\Contract\BroadcastPusherConfigContract;
use Valkyrja\Log\Logger\Contract\LoggerContract;

/**
 * An application config that implements every broadcast contract at once.
 *
 * The adapter contracts prefix each property with the adapter name, so one class
 * can carry the settings for several adapters without a name collision.
 */
final class BroadcastConfigFixture extends Config implements BroadcastConfigContract, BroadcastPusherConfigContract, BroadcastLogConfigContract
{
    /**
     * @param class-string<BroadcasterContract> $defaultBroadcaster
     * @param non-empty-string                  $pusherKey
     * @param non-empty-string                  $pusherSecret
     * @param non-empty-string                  $pusherId
     * @param non-empty-string                  $pusherCluster
     * @param class-string<LoggerContract>      $logLogger
     */
    public function __construct(
        public string $defaultBroadcaster = NullBroadcaster::class,
        public string $pusherKey = 'test-key',
        public string $pusherSecret = 'test-secret',
        public string $pusherId = 'test-id',
        public string $pusherCluster = 'eu',
        public bool $pusherUseTls = false,
        public string $logLogger = LoggerContract::class,
    ) {
        parent::__construct();
    }
}
