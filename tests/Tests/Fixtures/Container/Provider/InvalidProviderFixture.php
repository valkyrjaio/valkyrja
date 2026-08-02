<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Container\Provider;

use Override;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

final class InvalidProviderFixture implements ServiceProviderContract
{
    #[Override]
    public function publishers(): array
    {
        return [];
    }
}
