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

namespace Valkyrja\Validation\Rule;

use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract as OrmManager;
use Valkyrja\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Validation\Throwable\Exception\ValidationException;

use function is_bool;
use function is_float;
use function is_int;
use function is_string;

/**
 * Class Orm.
 *
 * @author Melech Mizrachi
 */
class Orm
{
    /**
     * ORM constructor.
     *
     * @param ContainerContract $container
     * @param OrmManager        $orm
     */
    public function __construct(
        protected ContainerContract $container,
        protected OrmManager $orm
    ) {
    }

    /**
     * Ensure that a subject is unique in the database for a field.
     *
     * @param mixed                        $subject The subject
     * @param class-string<EntityContract> $entity  The entity to check for uniqueness
     * @param non-empty-string|null        $field   The field to ensure is unique
     *
     * @return void
     */
    public function ormUnique(mixed $subject, string $entity, string|null $field = null): void
    {
        if ($subject !== null && ! is_string($subject) && ! is_int($subject) && ! is_float($subject) && ! is_bool($subject)) {
            throw new InvalidArgumentException('Value to match must be string, int, float, bool, or null');
        }

        $field ??= $entity::getIdField();
        // Check for a result
        $result = $this->orm->createRepository($entity)->findBy(new Where(new Value(name: $field, value: $subject)));

        if ($result !== null) {
            throw new ValidationException("Must be a unique value in $entity for field $field");
        }
    }

    /**
     * Ensure that a subject exists in the database for a field.
     *
     * @param mixed                        $subject The subject
     * @param class-string<EntityContract> $entity  The entity to check for uniqueness
     * @param non-empty-string|null        $field   The field to ensure is unique
     *
     * @return void
     */
    public function ormExists(mixed $subject, string $entity, string|null $field = null): void
    {
        if ($subject !== null && ! is_string($subject) && ! is_int($subject) && ! is_float($subject) && ! is_bool($subject)) {
            throw new InvalidArgumentException('Value to match must be string, int, float, bool, or null');
        }

        $field ??= $entity::getIdField();
        // Check for a result
        $result = $this->orm->createRepository($entity)->findBy(new Where(new Value(name: $field, value: $subject)));

        if ($result === null) {
            throw new ValidationException("Must exist in $entity for field $field");
        }

        // Set a singleton of the entity in the container for later retrieval
        $this->container->setSingleton($entity, $result);
    }
}
