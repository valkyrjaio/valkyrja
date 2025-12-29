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

use Override;
use Valkyrja\Container\Manager\Contract\Container;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandler;
use Valkyrja\Http\Routing\Data\Contract\Parameter;
use Valkyrja\Http\Routing\Data\Contract\Route;
use Valkyrja\Orm\Data\EntityCast;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Manager\Contract\Manager;
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
        protected Manager $orm,
        protected ResponseFactory $responseFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
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

        if ($parameters !== []) {
            $arguments    = $dispatch->getArguments() ?? [];
            $dependencies = $dispatch->getDependencies() ?? [];

            // Iterate through the params
            foreach ($parameters as $parameter) {
                $name = $parameter->getName();
                /** @psalm-suppress MixedAssignment */
                $value = $arguments[$name];
                $type  = $parameter->getCast()->type ?? null;

                if ($type === null || ! is_a($type, Entity::class, true)) {
                    continue;
                }

                $response = $this->checkParameterForEntity(
                    parameter: $parameter,
                    type: $type,
                    value: $value
                );

                if ($response instanceof Response) {
                    return $response;
                }

                unset($dependencies[$name]);

                $arguments[$name] = $response;
            }

            $route = $route->withDispatch(
                $dispatch
                    ->withDependencies($dependencies)
                    ->withArguments($arguments)
            );
        }

        return $route;
    }

    /**
     * Check a route's parameter for valid entity values.
     *
     * @param Parameter            $parameter The parameter
     * @param class-string<Entity> $type      The entity type
     * @param mixed                $value     The argument value
     *
     * @return Entity|Response
     */
    protected function checkParameterForEntity(Parameter $parameter, string $type, mixed $value): Entity|Response
    {
        if ($value instanceof Entity) {
            return $value;
        }

        if ((is_string($value) && $value !== '') || is_int($value)) {
            /** @var non-empty-string|int $value */
            return $this->findEntityFromParameter(
                parameter: $parameter,
                entityName: $type,
                value: $value
            )
                ?? $this->getNotFoundResponse(parameter: $parameter);
        }

        return $this->getBadRequestResponse(parameter: $parameter);
    }

    /**
     * Try to find a route's entity dependency.
     *
     * @param Parameter            $parameter  The parameter
     * @param class-string<Entity> $entityName The entity class name
     * @param non-empty-string|int $value      The value
     *
     * @return Entity|null
     */
    protected function findEntityFromParameter(
        Parameter $parameter,
        string $entityName,
        string|int $value
    ): Entity|null {
        $cast          = $parameter->getCast();
        $repository    = $this->orm->createRepository($entityName);
        $field         = null;
        $relationships = [];

        if ($cast instanceof EntityCast) {
            $relationships = $cast->relationships ?? [];
            $field         = $cast->column;
        }

        // If there is a field specified to use
        if ($field !== null && $field !== '') {
            return $repository->findBy(new Where(new Value(name: $field, value: $value)));
        }

        return $repository->find($value);
    }

    /**
     * Response for when the entity was not found with the given value.
     *
     * @param Parameter $parameter The parameter
     *
     * @return Response
     */
    protected function getNotFoundResponse(Parameter $parameter): Response
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
     * @param Parameter $parameter The parameter
     *
     * @return Response
     */
    protected function getBadRequestResponse(Parameter $parameter): Response
    {
        return $this->responseFactory
            ->createResponseFromView(
                template: "$this->errorsTemplateDir/" . ((string) StatusCode::BAD_REQUEST->value),
                statusCode: StatusCode::BAD_REQUEST,
            );
    }
}
