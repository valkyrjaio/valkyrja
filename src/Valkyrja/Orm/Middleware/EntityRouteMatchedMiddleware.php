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

namespace Valkyrja\Orm\Middleware;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Dispatcher\Data\Contract\ClassDispatch;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Routing\Data\Contract\Parameter;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Orm\Contract\Orm;
use Valkyrja\Orm\Data\EntityCast;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Repository\Contract\RelationshipRepository;
use Valkyrja\View\Factory\Contract\ResponseFactory;

use function is_a;
use function is_int;
use function is_string;

/**
 * Class EntityRouteMatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
class EntityRouteMatchedMiddleware implements RouteMatchedMiddleware
{
    /**
     * The errors template directory.
     *
     * @var string
     */
    protected string $errorsTemplateDir = 'errors';

    public function __construct(
        protected Container $container,
        protected Orm $orm,
        protected ResponseFactory $responseFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function routeMatched(ServerRequest $request, Route $route, RouteMatchedHandler $handler): Route|Response
    {
        $routeOrResponse = $this->checkRouteForEntities($route);

        if ($routeOrResponse instanceof Response) {
            return $routeOrResponse;
        }

        return $handler->routeMatched($request, $routeOrResponse);
    }

    /**
     * Check route for entities.
     *
     * @param Route $route The route
     *
     * @return Response|Route
     */
    protected function checkRouteForEntities(Route $route): Response|Route
    {
        $parameters = $route->getParameters();
        $dispatch   = $route->getDispatch();

        if ($parameters !== [] && $dispatch instanceof ClassDispatch) {
            $arguments    = $dispatch->getArguments() ?? [];
            $dependencies = $dispatch->getDependencies() ?? [];

            // Iterate through the params
            foreach ($parameters as $index => $parameter) {
                $response = $this->checkParameterForEntity((int) $index, $parameter, $dependencies, $arguments);

                if ($response !== null) {
                    return $response;
                }
            }

            $route = $route->withDispatch($dispatch->withArguments($arguments));
            $route = $route->withDispatch($dispatch->withDependencies($dependencies));
        }

        return $route;
    }

    /**
     * Check a route's parameters for an entity.
     *
     * @param int                     $index        The index
     * @param Parameter               $parameter    The parameter
     * @param string[]                $dependencies The route dependencies
     * @param array<array-key, mixed> $arguments    The arguments
     *
     * @return Response|null
     */
    protected function checkParameterForEntity(int $index, Parameter $parameter, array &$dependencies, array &$arguments): Response|null
    {
        $type = $parameter->getCast()->type ?? null;

        if ($type !== null && is_a($type, Entity::class, true)) {
            $match = $arguments[$index];

            if (! is_string($match) && ! is_int($match) && ! $match instanceof Entity) {
                return $this->getBadRequestResponse($type, $match);
            }

            return $this->findAndSetEntityFromParameter($index, $parameter, $type, $dependencies, $match);
        }

        return null;
    }

    /**
     * Try to find and set a route's entity dependency.
     *
     * @param int                  $index        The index
     * @param Parameter            $parameter    The parameter
     * @param class-string<Entity> $entityName   The entity class name
     * @param string[]             $dependencies The dependencies
     * @param Entity|string|int    $value        The value
     *
     * @return Response|null
     */
    protected function findAndSetEntityFromParameter(
        int $index,
        Parameter $parameter,
        string $entityName,
        array &$dependencies,
        Entity|string|int &$value
    ): Response|null {
        if ($value instanceof Entity) {
            return null;
        }

        // Attempt to get the entity from the ORM repository
        $entity = $this->findEntityFromParameter($parameter, $entityName, $value);

        if ($entity === null) {
            return $this->getNotFoundResponse($entityName, $value);
        }

        // Set the entity with the param name as the service id into the container
        $this->container->setSingleton($entityName . ((string) $index), $entity);

        // Replace the route match with this entity
        /** @param-out Entity $value */
        $value = $entity;

        $updatedDependencies = [];

        foreach ($dependencies as $dependency) {
            if ($dependency !== $entityName) {
                $updatedDependencies[] = $dependency;
            }
        }

        $dependencies = $updatedDependencies;

        return null;
    }

    /**
     * Try to find a route's entity dependency.
     *
     * @param Parameter            $parameter  The parameter
     * @param class-string<Entity> $entityName The entity class name
     * @param string|int           $value      The value
     *
     * @return Entity|null
     */
    protected function findEntityFromParameter(
        Parameter $parameter,
        string $entityName,
        string|int $value
    ): Entity|null {
        $cast          = $parameter->getCast();
        $repository    = $this->orm->getRepository($entityName);
        $field         = null;
        $relationships = [];

        if ($cast instanceof EntityCast) {
            $relationships = $cast->relationships ?? [];
            $field         = $cast->column;
        }

        // If there is a field specified to use
        if ($field !== null && $field !== '') {
            $find = $repository->find()->where($field, null, $value);

            if (is_a($find, RelationshipRepository::class)) {
                $find->withRelationships($relationships);
            }

            return $find->getOneOrNull();
        }

        $find = $repository->findOne($value);

        if (is_a($find, RelationshipRepository::class)) {
            $find->withRelationships($relationships);
        }

        return $find->getOneOrNull();
    }

    /**
     * Response for when the entity was not found with the given value.
     *
     * @param string $entity The entity not found
     * @param mixed  $value  [optional] The value used to check for the entity
     *
     * @return Response
     */
    protected function getNotFoundResponse(string $entity, mixed $value): Response
    {
        return $this->responseFactory
            ->createResponseFromView(
                template: "$this->errorsTemplateDir/" . ((string) StatusCode::NOT_FOUND->value),
                statusCode: StatusCode::NOT_FOUND,
            );
    }

    /**
     * Response for when bad data has been provided to match for the entity.
     *
     * @param string $entity The entity with bad data
     * @param mixed  $value  [optional] The bad data value
     *
     * @return Response
     */
    protected function getBadRequestResponse(string $entity, mixed $value): Response
    {
        return $this->responseFactory
            ->createResponseFromView(
                template: "$this->errorsTemplateDir/" . ((string) StatusCode::BAD_REQUEST->value),
                statusCode: StatusCode::BAD_REQUEST,
            );
    }
}
