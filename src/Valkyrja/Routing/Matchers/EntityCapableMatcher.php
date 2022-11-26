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
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\ORM;
use Valkyrja\Routing\Collection;
use Valkyrja\Routing\Enums\CastType;
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
    protected function getMatchValueForType(Route $route, Parameter $parameter, CastType $castType, int $index, mixed $match): mixed
    {
        // If this is an entity cast type
        if ($castType === CastType::entity) {
            if (! $entityName = $parameter->getEntity()) {
                throw new InvalidRouteParameter("Entity is missing for casted entity parameter $parameter->name");
            }

            // Try to get the entity
            $entity = $this->getEntity($parameter, $entityName, $match);

            // If no entity is found
            if ($entity === null) {
                // Handle entity not found
                $this->handleEntityNotFound($parameter, $match);
            }

            // Set the entity with the param name as the service id into the container
            $this->container->setSingleton($entityName . $index, $entity);
            $this->removeEntityFromDependencies($route, $entityName);

            return $entity;
        }

        return parent::getMatchValueForType($route, $parameter, $castType, $index, $match);
    }

    /**
     * Get the entity.
     *
     * @param Parameter $parameter  The parameter
     * @param string    $entityName The entity class name
     * @param mixed     $match      The match value
     *
     * @return Entity|null
     */
    protected function getEntity(Parameter $parameter, string $entityName, mixed $match): ?Entity
    {
        $orm = $this->container->getSingleton(ORM::class)->getRepository($entityName);

        $relationships = $parameter->getEntityRelationships() ?? [];

        // If there is a field specified to use
        if ($field = $parameter->getEntityColumn()) {
            return $orm->find()
                ->where($field, null, $match)
                ->withRelationships($relationships)
                ->getOneOrNull();
        }

        return $orm->findOne($match)
            ->withRelationships($relationships)
            ->getOneOrNull();
    }

    /**
     * Remove the entity from the route's dependencies list.
     *
     * @param Route  $route      The route
     * @param string $entityName The entity class name
     *
     * @return void
     */
    protected function removeEntityFromDependencies(Route $route, string $entityName): void
    {
        $updatedDependencies = [];

        foreach ($route->getDependencies() as $dependency) {
            if ($dependency !== $entityName) {
                $updatedDependencies[] = $dependency;
            }
        }

        $route->setDependencies($updatedDependencies);
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
