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
use Valkyrja\Path\Constants\PathSeparator;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Support\Abort;
use Valkyrja\Support\Type\Cls;
use Valkyrja\Support\Type\Str;

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
        $params       = $route->getParams() ?? [];
        $dependencies = $route->getDependencies() ?? [];
        $counter      = 0;

        // Iterate through the params
        foreach ($params as $param => $paramValue) {
            $counter++;

            static::checkParamForEntity($param, $dependencies, $matches, $counter);
        }

        $route->setMatches($matches);
        $route->setDependencies($dependencies);
    }

    /**
     * Check a route param for entity.
     *
     * @param string $param             The params
     * @param array  $routeDependencies The route dependencies
     * @param array  $matches           The matches
     * @param int    $counter           The counter
     *
     * @return void
     */
    protected static function checkParamForEntity(
        string $param,
        array &$routeDependencies,
        array &$matches,
        int $counter
    ): void {
        if (! Str::contains($param, PathSeparator::ENTITY_CLASS)) {
            return;
        }

        // Get the class
        [$matchName, $class] = explode(PathSeparator::ENTITY_CLASS, $param);

        // Check if a param
        if ($class && Cls::inherits($class, Entity::class)) {
            static::checkDependenciesForEntities($param, $routeDependencies, $matches, $matchName, $class, $counter);
        }
    }

    /**
     * Check dependencies for entities.
     *
     * @param string $param             The params
     * @param array  $routeDependencies The route dependencies
     * @param array  $matches           The matches
     * @param string $matchName         The match name
     * @param string $class             The entity class
     * @param int    $counter           The counter
     *
     * @return void
     */
    protected static function checkDependenciesForEntities(
        string $param,
        array &$routeDependencies,
        array &$matches,
        string $matchName,
        string $class,
        int $counter
    ): void {
        $dependencies = [];
        // Set found to false for this param
        $found = false;

        foreach ($routeDependencies as $dependencyKey => $dependency) {
            if (! $found && $class === $dependency) {
                static::findAndSetEntity($matchName, $class, $param, $matches[$counter]);

                // Set found to true in case another dependency is also the same class
                $found = true;

                continue;
            }

            $dependencies[] = $dependency;
        }

        $routeDependencies = $dependencies;
    }

    /**
     * Find and set an entity.
     *
     * @param string $matchName The match name
     * @param string $class     The entity class
     * @param string $param     The param name
     * @param mixed  $value     [optional] The value
     *
     * @return void
     */
    protected static function findAndSetEntity(string $matchName, string $class, string $param, &$value): void
    {
        // Attempt to get the entity from the ORM repository
        $entity = static::findEntity($matchName, $class, $value);

        if (! $entity) {
            static::entityNotFound($class, $value);
        }

        // Set the entity with the param name as the service id into the container
        self::$container->setSingleton($param, $entity);

        // Replace the route match with this entity
        $value = $entity;
    }

    /**
     * Find an entity.
     *
     * @param string $matchName The match name
     * @param string $entity    The entity class
     * @param mixed  $value     [optional] The value
     *
     * @return Entity|null
     */
    protected static function findEntity(string $matchName, string $entity, $value): ?Entity
    {
        if (Str::contains($matchName, PathSeparator::ENTITY_WITH_RELATIONSHIPS)) {
            [$matchName, $relationships] = explode(PathSeparator::ENTITY_WITH_RELATIONSHIPS, $matchName);

            $relationships = explode(PathSeparator::ENTITY_RELATIONSHIPS, $relationships);
        }

        // If there is a field specified to use
        if (Str::contains($matchName, PathSeparator::ENTITY_FIELD)) {
            // Let's split the match name and use the field name
            [, $field] = explode(PathSeparator::ENTITY_FIELD, $matchName);

            return static::getOrmRepository($entity)
                         ->find()
                         ->where($field, null, $value)
                         ->withRelationships($relationships ?? [])
                         ->getOneOrNull();
        }

        return static::getOrmRepository($entity)
                     ->findOne($value)
                     ->withRelationships($relationships ?? [])
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
