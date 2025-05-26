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

namespace Valkyrja\Event\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Event\Model\Contract\Listener;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 *
 * @psalm-type CacheAsArray array{events: array<class-string, string[]>, listeners: Listener[]}
 *
 * @phpstan-type CacheAsArray array{events: array<class-string, string[]>, listeners: Listener[]}
 */
class Cache extends Model
{
    /**
     * The events.
     *
     * @var array<class-string, string[]>
     */
    public array $events;

    /**
     * The listeners.
     *
     * @var array<string, Listener|string>
     */
    public array $listeners;
}
