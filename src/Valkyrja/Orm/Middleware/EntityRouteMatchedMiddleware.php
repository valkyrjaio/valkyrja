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
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Routing\Model\Contract\Route;
use Valkyrja\Http\Routing\Model\Parameter\Parameter;
use Valkyrja\Orm\Contract\Orm;
use Valkyrja\Orm\Data\EntityCast;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Repository\Contract\RelationshipRepository;
use Valkyrja\View\Factory\Contract\ResponseFactory;

use function is_a;

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

        if ($parameters !== []) {
            $matches      = $route->getMatches() ?? [];
            $dependencies = $route->getDependencies() ?? [];

            // Iterate through the params
            foreach ($parameters as $index => $parameter) {
                $response = $this->checkParameterForEntity($index, $parameter, $dependencies, $matches);

                if ($response !== null) {
                    return $response;
                }
            }

            $route->setMatches($matches);
            $route->setDependencies($dependencies);
        }

        return $route;
    }

    /**
     * Check a route's parameters for an entity.
     *
     * @param int               $index        The index
     * @param Parameter         $parameter    The parameter
     * @param string[]          $dependencies The route dependencies
     * @param array<int, mixed> $matches      The matches
     *
     * @return Response|null
     */
    protected function checkParameterForEntity(int $index, Parameter $parameter, array &$dependencies, array &$matches): Response|null
    {
        $entityName = $parameter->getCast()->type ?? null;

        if (is_a($entityName, Entity::class, true)) {
            return $this->findAndSetEntityFromParameter($index, $parameter, $entityName, $dependencies, $matches[$index]);
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
     * @param mixed                $value        The value
     *
     * @return Response|null
     */
    protected function findAndSetEntityFromParameter(
        int $index,
        Parameter $parameter,
        string $entityName,
        array &$dependencies,
        mixed &$value
    ): Response|null {
        // Attempt to get the entity from the ORM repository
        $entity = $this->findEntityFromParameter($parameter, $entityName, $value);

        if ($entity === null) {
            return $this->entityNotFound($entityName, $value);
        }

        // Set the entity with the param name as the service id into the container
        $this->container->setSingleton($entityName . $index, $entity);

        // Replace the route match with this entity
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
     * @param mixed                $value      The value
     *
     * @return Entity|null
     */
    protected function findEntityFromParameter(
        Parameter $parameter,
        string $entityName,
        mixed $value
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
     * Do when an entity was not found with the given value.
     *
     * @param string $entity The entity not found
     * @param mixed  $value  [optional] The value used to check for the entity
     *
     * @return Response
     */
    protected function entityNotFound(string $entity, mixed $value): Response
    {
        return $this->responseFactory
            ->createResponseFromView(
                template: "$this->errorsTemplateDir/" . StatusCode::NOT_FOUND->value,
                statusCode: StatusCode::NOT_FOUND,
            );
    }
}
