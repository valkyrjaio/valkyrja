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
