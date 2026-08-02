<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Container;

use Valkyrja\Container\Manager\Contract\ContainerContract;

/**
 * Testable Service class.
 */
final class ServiceFixture
{
    public function __construct(
        public ContainerContract $container,
    ) {
    }

    public static function make(ContainerContract $container, array $arguments = []): static
    {
        return new self($container);
    }

    public function getContainer(): ContainerContract
    {
        return $this->container;
    }
}
