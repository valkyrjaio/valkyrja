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

namespace Valkyrja\Auth\Policies;

use Valkyrja\Auth\EntityPolicy as Contract;
use Valkyrja\Auth\Repository;
use Valkyrja\ORM\Entity;

/**
 * Abstract Class EntityPolicy.
 *
 * @author Melech Mizrachi
 */
abstract class EntityPolicy extends Policy implements Contract
{
    /**
     * The entity class name.
     *
     * @var string
     */
    protected static string $entityClassName;

    /**
     * The entity param number.
     *
     * @var int
     */
    protected static int $entityParamNumber = 0;

    /**
     * The entity.
     *
     * @var Entity
     */
    protected Entity $entity;

    /**
     * Policy constructor.
     *
     * @param Repository $repository The repository
     */
    public function __construct(Repository $repository, Entity $entity)
    {
        parent::__construct($repository);

        $this->entity = $entity;
    }

    /**
     * Get the entity class name that's associated with this policy.
     *
     * @return string
     */
    public static function getEntityClassName(): string
    {
        return static::$entityClassName;
    }

    /**
     * Get the entity param number.
     *  For example if the route was defined as `get('/path/{entity1}/entity2')` with an action of
     *  `action(Entity1 $entity1, Entity2 $entity2)` you would set 1 to get the first entity or
     *  2 for the second, etc.
     *
     * @return int
     */
    public static function getEntityParamNumber(): int
    {
        return static::$entityParamNumber;
    }
}
