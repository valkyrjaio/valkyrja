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

use Valkyrja\Container\Manager\Trait\ProvidersAware;

use function array_key_exists;

/**
 * Class ProvidersAwareFixture.
 */
final class ProvidersAwareFixture
{
    use ProvidersAware;

    private array $objects = [];

    public function __get(string $name)
    {
        return $this->objects[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->objects[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->objects);
    }

    /**
     * @param class-string $id The service id
     */
    public function callPublishUnpublishedProvided(string $id): void
    {
        $this->publishUnpublishedProvided($id);
    }
}
