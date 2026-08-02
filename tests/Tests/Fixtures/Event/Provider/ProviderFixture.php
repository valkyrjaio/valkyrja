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
use Valkyrja\Event\Provider\Contract\ListenerProviderContract;

final class ProviderFixture implements ListenerProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getListenerClasses(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getListeners(): array
    {
        return [];
    }
}
