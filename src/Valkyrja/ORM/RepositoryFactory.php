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

namespace Valkyrja\ORM;

/**
 * Interface RepositoryFactory.
 *
 * @author Melech Mizrachi
 */
interface RepositoryFactory
{
    /**
     * Create a repository.
     *
     * @template T
     * @template E
     *
     * @param Driver          $driver The driver
     * @param class-string<T> $name   The repository class name
     * @param class-string<E> $entity The entity class name
     *
     * @return T<E>
     */
    public function createRepository(Driver $driver, string $name, string $entity): Repository;
}
