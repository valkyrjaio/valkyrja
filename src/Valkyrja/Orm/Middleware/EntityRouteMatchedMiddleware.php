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
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Orm\Data\EntityCast;
use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\View\Factory\Contract\ResponseFactoryContract;

use function is_a;
use function is_int;
use function is_string;

/**
 * Class EntityRouteMatchedMiddleware.
 */
class EntityRouteMatchedMiddleware implements RouteMatchedMiddlewareContract
{
    /**
     * The errors template directory.
     *
     * @var string
     */
    protected string $errorsTemplateDir = 'errors';

    public function __construct(
        protected ContainerContract $container,
        protected ManagerContract $orm,
        protected ResponseFactoryContract $responseFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function routeMatched(ServerRequestContract $request, RouteContract $route, RouteMatchedHandlerContract $handler): RouteContract|ResponseContract
    {
        $routeOrResponse = $this->checkRouteForEntities($route);

        if ($routeOrResponse instanceof ResponseContract) {
            return $routeOrResponse;
        }

        return $handler->routeMatched($request, $routeOrResponse);
    }

    /**
     * Check route for entities.
     *
     * @param RouteContract $route The route
     *
     * @return ResponseContract|RouteContract
     */
    protected function checkRouteForEntities(RouteContract $route): ResponseContract|RouteContract
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

                if ($type === null || ! is_a($type, EntityContract::class, true)) {
                    continue;
                }

                $response = $this->checkParameterForEntity(
                    parameter: $parameter,
                    type: $type,
                    value: $value
                );

                if ($response instanceof ResponseContract) {
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
     * @param ParameterContract            $parameter The parameter
     * @param class-string<EntityContract> $type      The entity type
     * @param mixed                        $value     The argument value
     *
     * @return EntityContract|ResponseContract
     */
    protected function checkParameterForEntity(ParameterContract $parameter, string $type, mixed $value): EntityContract|ResponseContract
    {
        if ($value instanceof EntityContract) {
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
     * @param ParameterContract            $parameter  The parameter
     * @param class-string<EntityContract> $entityName The entity class name
     * @param non-empty-string|int         $value      The value
     *
     * @return EntityContract|null
     */
    protected function findEntityFromParameter(
        ParameterContract $parameter,
        string $entityName,
        string|int $value
    ): EntityContract|null {
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
     * @param ParameterContract $parameter The parameter
     *
     * @return ResponseContract
     */
    protected function getNotFoundResponse(ParameterContract $parameter): ResponseContract
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
     * @param ParameterContract $parameter The parameter
     *
     * @return ResponseContract
     */
    protected function getBadRequestResponse(ParameterContract $parameter): ResponseContract
    {
        return $this->responseFactory
            ->createResponseFromView(
                template: "$this->errorsTemplateDir/" . ((string) StatusCode::BAD_REQUEST->value),
                statusCode: StatusCode::BAD_REQUEST,
            );
    }
}
