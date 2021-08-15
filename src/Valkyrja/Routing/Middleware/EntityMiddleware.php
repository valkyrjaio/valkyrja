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

namespace Valkyrja\Routing\Middleware;

use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\ORM\Entity;
use Valkyrja\ORM\ORM;
use Valkyrja\ORM\Repository;
use Valkyrja\Routing\Models\Parameter;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Support\Abort;

/**
 * Class EntityMiddleware.
 *
 * @author Melech Mizrachi
 */
class EntityMiddleware extends RouteMiddleware
{
    protected static ORM $orm;

    /**
     * Middleware handler for before a request is dispatched.
     *
     * @param Request $request The request
     *
     * @return Request|Response
     */
    public static function before(Request $request)
    {
        if (($route = self::$route ?? null) && $matches = $route->getMatches()) {
            static::checkParamsForEntities($route, $matches);
        }

        return $request;
    }

    /**
     * Get the ORM repository for a given entity.
     *
     * @param string $entity
     *
     * @return Repository
     */
    protected static function getOrmRepository(string $entity): Repository
    {
        return static::getOrm()->getRepository($entity);
    }

    /**
     * Get the ORM service.
     *
     * @return ORM
     */
    protected static function getOrm(): ORM
    {
        return self::$orm ?? self::$container->getSingleton(ORM::class);
    }

    /**
     * Check route params for entities.
     *
     * @param Route $route   The route
     * @param array $matches The matches
     *
     * @return void
     */
    protected static function checkParamsForEntities(Route $route, array $matches): void
    {
        $parameters = $route->getParameters();

        if (! empty($parameters)) {
            $dependencies = $route->getDependencies() ?? [];

            // Iterate through the params
            foreach ($parameters as $index => $parameter) {
                static::checkParameterForEntity($index, $parameter, $dependencies, $matches);
            }

            $route->setMatches($matches);
            $route->setDependencies($dependencies);
        }
    }

    /**
     * Check a route's parameters for an entity.
     *
     * @param int       $index        The index
     * @param Parameter $parameter    The parameter
     * @param array     $dependencies The route dependencies
     * @param array     $matches      The matches
     *
     * @return void
     */
    protected static function checkParameterForEntity(int $index, Parameter $parameter, array &$dependencies, array &$matches): void
    {
        if ($parameter->getEntity() === null) {
            return;
        }

        static::findAndSetEntityFromParameter($index, $parameter, $dependencies, $matches[$index]);
    }

    /**
     * Try to find and set a route's entity dependency.
     *
     * @param int       $index        The index
     * @param Parameter $parameter    The parameter
     * @param array     $dependencies The dependencies
     * @param mixed     $value        The value
     *
     * @return void
     */
    protected static function findAndSetEntityFromParameter(int $index, Parameter $parameter, array &$dependencies, &$value): void
    {
        $entityName = $parameter->getEntity();
        // Attempt to get the entity from the ORM repository
        $entity = static::findEntityFromParameter($parameter, $value);

        if (! $entity) {
            static::entityNotFound($entityName, $value);
        }

        // Set the entity with the param name as the service id into the container
        self::$container->setSingleton($entityName . $index, $entity);

        // Replace the route match with this entity
        $value = $entity;

        $updatedDependencies = [];

        foreach ($dependencies as $dependency) {
            if ($dependency !== $entityName) {
                $updatedDependencies[] = $dependency;
            }
        }

        $dependencies = $updatedDependencies;
    }

    /**
     * Try to find a route's entity dependency.
     *
     * @param Parameter $parameter The parameter
     * @param mixed     $value     The value
     *
     * @return Entity
     */
    protected static function findEntityFromParameter(Parameter $parameter, $value): Entity
    {
        $entityName    = $parameter->getEntity();
        $relationships = $parameter->getEntityRelationships() ?? [];

        // If there is a field specified to use
        if ($field = $parameter->getEntityColumn()) {
            return static::getOrmRepository($entityName)
                ->find()
                ->where($field, null, $value)
                ->withRelationships($relationships)
                ->getOneOrNull();
        }

        return static::getOrmRepository($entityName)
            ->findOne($value)
            ->withRelationships($relationships)
            ->getOneOrNull();
    }

    /**
     * Do when an entity was not found with the given value.
     *
     * @param string $entity The entity not found
     * @param mixed  $value  [optional] The value used to check for the entity
     *
     * @return void
     */
    protected static function entityNotFound(string $entity, $value): void
    {
        Abort::abort404();
    }
}
