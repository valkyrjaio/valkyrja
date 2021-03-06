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

namespace Valkyrja\Validation\Rules;

use Valkyrja\Container\Container;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\ORM as ORMManager;
use Valkyrja\Validation\Exceptions\ValidationException;

/**
 * Class ORM.
 *
 * @author Melech Mizrachi
 */
class ORM
{
    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The ORM manager.
     *
     * @var ORMManager
     */
    protected ORMManager $orm;

    /**
     * ORM constructor.
     *
     * @param Container  $container
     * @param ORMManager $orm
     */
    public function __construct(Container $container, ORMManager $orm)
    {
        $this->container = $container;
        $this->orm       = $orm;
    }

    /**
     * Ensure that a subject is unique in the database for a field.
     *
     * @param mixed       $subject The subject
     * @param string      $entity  The entity to check for uniqueness
     * @param string|null $field   The field to ensure is unique
     *
     * @return void
     */
    public function ormUnique($subject, string $entity, string $field = null): void
    {
        /** @var Entity|string $entity */
        $field ??= $entity::getIdField();
        // Check for a result
        $result = $this->orm->getRepository($entity)->find()->where($field, null, $subject)->getOneOrNull();

        // Set a singleton of the entity in the container for later retrieval
        $this->container->setSingleton($entity, $result);

        if (null !== $result) {
            throw new ValidationException("{$subject} must be a unique value in {$entity} for field {$field}");
        }
    }

    /**
     * Ensure that a subject exists in the database for a field.
     *
     * @param mixed       $subject The subject
     * @param string      $entity  The entity to check for uniqueness
     * @param string|null $field   The field to ensure is unique
     *
     * @return void
     */
    public function ormExists($subject, string $entity, string $field = null): void
    {
        /** @var Entity|string $entity */
        $field ??= $entity::getIdField();
        // Check for a result
        $result = $this->orm->getRepository($entity)->find()->where($field, null, $subject)->getOneOrNull();

        // Set a singleton of the entity in the container for later retrieval
        $this->container->setSingleton($entity, $result);

        if (null === $result) {
            throw new ValidationException("{$subject} must exist in {$entity} for field {$field}");
        }
    }
}
