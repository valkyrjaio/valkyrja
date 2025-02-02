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

use Valkyrja\Container\Contract\Container;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Orm\Contract\Orm as ORMManager;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Validation\Exception\ValidationException;

use function is_bool;
use function is_float;
use function is_int;
use function is_string;

/**
 * Class ORM.
 *
 * @author Melech Mizrachi
 */
class ORM
{
    /**
     * ORM constructor.
     *
     * @param Container  $container
     * @param ORMManager $orm
     */
    public function __construct(
        protected Container $container,
        protected ORMManager $orm
    ) {
    }

    /**
     * Ensure that a subject is unique in the database for a field.
     *
     * @param mixed                $subject The subject
     * @param class-string<Entity> $entity  The entity to check for uniqueness
     * @param string|null          $field   The field to ensure is unique
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
        $result = $this->orm->getRepository($entity)->find()->where($field, value: $subject)->getOneOrNull();

        // Set a singleton of the entity in the container for later retrieval
        $this->container->setSingleton($entity, $result);

        if ($result !== null) {
            throw new ValidationException("Must be a unique value in $entity for field $field");
        }
    }

    /**
     * Ensure that a subject exists in the database for a field.
     *
     * @param mixed                $subject The subject
     * @param class-string<Entity> $entity  The entity to check for uniqueness
     * @param string|null          $field   The field to ensure is unique
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
        $result = $this->orm->getRepository($entity)->find()->where($field, value: $subject)->getOneOrNull();

        // Set a singleton of the entity in the container for later retrieval
        $this->container->setSingleton($entity, $result);

        if ($result === null) {
            throw new ValidationException("Must exist in $entity for field $field");
        }
    }
}
