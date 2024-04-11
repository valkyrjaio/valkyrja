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

namespace Valkyrja\Routing\Matchers;

use Valkyrja\Container\Container;
use Valkyrja\Model\Data\Cast;
use Valkyrja\Orm\Entity;
use Valkyrja\Orm\Orm;
use Valkyrja\Orm\RelationshipRepository;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Data\EntityCast;
use Valkyrja\Routing\Exceptions\InvalidRouteParameter;
use Valkyrja\Routing\Models\Parameter;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Support\Abort;

/**
 * Class EntityCapableMatcher.
 *
 * @author Melech Mizrachi
 */
class EntityCapableMatcher extends Matcher
{
    /**
     * EntityCapableMatcher constructor.
     *
     * @param Collection $collection The collection
     */
    public function __construct(
        protected Container $container,
        Collection $collection,
    ) {
        parent::__construct($collection);
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRouteParameter
     */
    protected function castMatchValue(Route $route, Parameter $parameter, Cast $cast, int $index, mixed $match): mixed
    {
        // If this is not an entity cast type
        if (! is_a($cast->type, Entity::class, true)) {
            return parent::castMatchValue($route, $parameter, $cast, $index, $match);
        }

        $entityName = $cast->type;

        // Try to get the entity
        $entity = $this->getEntity($parameter, $entityName, $match);

        // If no entity is found
        if ($entity === null) {
            // Handle entity not found
            $this->handleEntityNotFound($parameter, $match);
        }

        // Set the entity with the param name as the service id into the container
        $this->container->setSingleton($entityName . $index, $entity);

        return $entity;
    }

    /**
     * Get the entity.
     *
     * @param Parameter            $parameter  The parameter
     * @param class-string<Entity> $entityName The entity class name
     * @param mixed                $match      The match value
     *
     * @return Entity|null
     */
    protected function getEntity(Parameter $parameter, string $entityName, mixed $match): Entity|null
    {
        $cast          = $parameter->getCast();
        $orm           = $this->container->getSingleton(Orm::class)->getRepository($entityName);
        $field         = null;
        $relationships = [];

        if ($cast instanceof EntityCast) {
            $relationships = $cast->relationships ?? [];
            $field         = $cast->column;
        }

        // If there is a field specified to use
        if ($field !== null && $field !== '') {
            $find = $orm->find()->where($field, null, $match);

            if (is_a($find, RelationshipRepository::class)) {
                $find->withRelationships($relationships);
            }

            return $find->getOneOrNull();
        }

        $find = $orm->findOne($match);

        if (is_a($find, RelationshipRepository::class)) {
            $find->withRelationships($relationships);
        }

        return $find->getOneOrNull();
    }

    /**
     * Handle the entity not being found.
     *
     * @param Parameter $parameter The parameter
     * @param mixed     $match     The match
     *
     * @return never
     */
    protected function handleEntityNotFound(Parameter $parameter, mixed $match): never
    {
        Abort::abort404();
    }
}
