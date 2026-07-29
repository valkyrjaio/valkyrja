<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Event\Provider;

use Override;
use Valkyrja\Event\Data\Listener;
use Valkyrja\Event\Provider\Contract\ListenerProviderContract;
use Valkyrja\Tests\Fixtures\Event\EventFixture;

final class ListenerProviderFixture implements ListenerProviderContract
{
    #[Override]
    public function getListenerClasses(): array
    {
        return [EventFixture::class];
    }

    #[Override]
    public function getListeners(): array
    {
        return [
            new Listener(
                eventId: self::class,
                name: 'listener-from-provider-name',
                handler: static fn () => null
            ),
        ];
    }
}
