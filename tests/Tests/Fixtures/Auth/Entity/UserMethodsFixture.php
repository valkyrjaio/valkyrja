<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Auth\Entity;

use Valkyrja\Auth\Entity\Trait\UserMethods;

/**
 * The smallest carrier for the UserMethods trait, backing its field lookups with a
 * plain array so a test can hand it any value the trait has to cope with.
 */
final class UserMethodsFixture
{
    use UserMethods;

    /**
     * @param array<string, mixed> $data The data
     */
    public function __construct(
        private array $data = []
    ) {
    }

    public function __get(string $name): mixed
    {
        return $this->data[$name] ?? null;
    }
}
