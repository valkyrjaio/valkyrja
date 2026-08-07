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
 * A carrier for the UserMethods trait, backed by a plain array.
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
