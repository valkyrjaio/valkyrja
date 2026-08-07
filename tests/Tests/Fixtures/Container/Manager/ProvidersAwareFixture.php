<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Container\Manager;

use Valkyrja\Container\Manager\Container;

/**
 * Class ProvidersAwareFixture.
 */
final class ProvidersAwareFixture extends Container
{
    /**
     * @param class-string $id The service id
     */
    public function callPublishUnpublishedProvided(string $id): void
    {
        $this->publishUnpublishedProvided($id);
    }
}
