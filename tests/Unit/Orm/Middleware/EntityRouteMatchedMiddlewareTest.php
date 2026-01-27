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

namespace Valkyrja\Tests\Unit\Orm\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Orm\Data\EntityCast;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Orm\Middleware\EntityRouteMatchedMiddleware;
use Valkyrja\Orm\Repository\Contract\RepositoryContract;
use Valkyrja\Tests\Classes\Orm\Entity\EntityClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;
use Valkyrja\View\Factory\Contract\ResponseFactoryContract;

class EntityRouteMatchedMiddlewareTest extends TestCase
{
    protected ContainerContract&MockObject $container;

    protected ManagerContract&MockObject $orm;

    protected ResponseFactoryContract&MockObject $responseFactory;

    protected EntityRouteMatchedMiddleware $middleware;

    protected function setUp(): void
    {
        $this->container       = $this->createMock(ContainerContract::class);
        $this->orm             = $this->createMock(ManagerContract::class);
        $this->responseFactory = $this->createMock(ResponseFactoryContract::class);

        $this->middleware = new EntityRouteMatchedMiddleware(
            $this->container,
            $this->orm,
            $this->responseFactory
        );
    }

    public function testImplementsRouteMatchedMiddlewareContract(): void
    {
        $this->container->expects($this->never())->method('has');
        $this->orm->expects($this->never())->method('createRepository');
        $this->responseFactory->expects($this->never())->method('createResponseFromView');

        self::assertInstanceOf(RouteMatchedMiddlewareContract::class, $this->middleware);
    }

    public function testRouteMatchedWithNoParameters(): void
    {
        $this->container->expects($this->never())->method('get');
        $this->orm->expects($this->never())->method('createRepository');
        $this->responseFactory->expects($this->never())->method('createResponseFromView');

        $request = self::createStub(ServerRequestContract::class);
        $route   = $this->createMock(RouteContract::class);
        $handler = $this->createMock(RouteMatchedHandlerContract::class);

        $route
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([]);

        $handler
            ->expects($this->once())
            ->method('routeMatched')
            ->with($request, $route)
            ->willReturn($route);

        $result = $this->middleware->routeMatched($request, $route, $handler);

        self::assertSame($route, $result);
    }

    public function testRouteMatchedWithNonEntityParameter(): void
    {
        $this->container->expects($this->never())->method('get');
        $this->orm->expects($this->never())->method('createRepository');
        $this->responseFactory->expects($this->never())->method('createResponseFromView');

        $request   = self::createStub(ServerRequestContract::class);
        $route     = $this->createMock(RouteContract::class);
        $handler   = $this->createMock(RouteMatchedHandlerContract::class);
        $dispatch  = $this->createMock(MethodDispatchContract::class);
        $parameter = $this->createMock(ParameterContract::class);
        $cast      = new Cast(type: CastType::string);

        $parameter
            ->expects($this->once())
            ->method('getName')
            ->willReturn('id');

        $parameter
            ->expects($this->once())
            ->method('getCast')
            ->willReturn($cast);

        $route
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([$parameter]);

        $route
            ->expects($this->once())
            ->method('getDispatch')
            ->willReturn($dispatch);

        $dispatch
            ->expects($this->once())
            ->method('getArguments')
            ->willReturn(['id' => '123']);

        $dispatch
            ->expects($this->once())
            ->method('getDependencies')
            ->willReturn([]);

        $handler
            ->expects($this->once())
            ->method('routeMatched')
            ->willReturn($route);

        $result = $this->middleware->routeMatched($request, $route, $handler);

        self::assertInstanceOf(RouteContract::class, $result);
    }

    public function testRouteMatchedWithEntityParameterAlreadyResolved(): void
    {
        $this->container->expects($this->never())->method('get');
        $this->orm->expects($this->never())->method('createRepository');
        $this->responseFactory->expects($this->never())->method('createResponseFromView');

        $request   = self::createStub(ServerRequestContract::class);
        $route     = $this->createMock(RouteContract::class);
        $handler   = $this->createMock(RouteMatchedHandlerContract::class);
        $dispatch  = $this->createMock(MethodDispatchContract::class);
        $parameter = $this->createMock(ParameterContract::class);
        $entity    = self::createStub(EntityContract::class);

        $entityClass = $entity::class;
        $cast        = new Cast(type: $entityClass);

        $parameter
            ->expects($this->once())
            ->method('getName')
            ->willReturn('user');

        $parameter
            ->expects($this->once())
            ->method('getCast')
            ->willReturn($cast);

        $route
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([$parameter]);

        $route
            ->expects($this->once())
            ->method('getDispatch')
            ->willReturn($dispatch);

        $route
            ->expects($this->once())
            ->method('withDispatch')
            ->willReturnSelf();

        $dispatch
            ->expects($this->once())
            ->method('getArguments')
            ->willReturn(['user' => $entity]);

        $dispatch
            ->expects($this->once())
            ->method('getDependencies')
            ->willReturn(['user' => $entityClass]);

        $dispatch
            ->expects($this->once())
            ->method('withDependencies')
            ->willReturnSelf();

        $dispatch
            ->expects($this->once())
            ->method('withArguments')
            ->willReturnSelf();

        $handler
            ->expects($this->once())
            ->method('routeMatched')
            ->willReturn($route);

        $result = $this->middleware->routeMatched($request, $route, $handler);

        self::assertInstanceOf(RouteContract::class, $result);
    }

    public function testRouteMatchedReturnsNotFoundResponseWhenEntityNotFound(): void
    {
        $this->container->expects($this->never())->method('get');

        $request    = self::createStub(ServerRequestContract::class);
        $route      = $this->createMock(RouteContract::class);
        $handler    = $this->createMock(RouteMatchedHandlerContract::class);
        $dispatch   = $this->createMock(MethodDispatchContract::class);
        $parameter  = $this->createMock(ParameterContract::class);
        $repository = $this->createMock(RepositoryContract::class);
        $response   = self::createStub(ResponseContract::class);

        $entityClass = EntityClass::class;
        $cast        = new Cast(type: $entityClass);

        $parameter
            ->expects($this->once())
            ->method('getName')
            ->willReturn('user');

        $parameter
            ->expects($this->exactly(2))
            ->method('getCast')
            ->willReturn($cast);

        $route
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([$parameter]);

        $route
            ->expects($this->once())
            ->method('getDispatch')
            ->willReturn($dispatch);

        $dispatch
            ->expects($this->once())
            ->method('getArguments')
            ->willReturn(['user' => 999]);

        $dispatch
            ->expects($this->once())
            ->method('getDependencies')
            ->willReturn(['user' => $entityClass]);

        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->with($entityClass)
            ->willReturn($repository);

        $repository
            ->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $this->responseFactory
            ->expects($this->once())
            ->method('createResponseFromView')
            ->with('errors/404', null, StatusCode::NOT_FOUND, null)
            ->willReturn($response);

        $handler
            ->expects($this->never())
            ->method('routeMatched');

        $result = $this->middleware->routeMatched($request, $route, $handler);

        self::assertInstanceOf(ResponseContract::class, $result);
    }

    public function testRouteMatchedReturnsBadRequestResponseForInvalidValue(): void
    {
        $this->container->expects($this->never())->method('get');
        $this->orm->expects($this->never())->method('createRepository');

        $request   = self::createStub(ServerRequestContract::class);
        $route     = $this->createMock(RouteContract::class);
        $handler   = $this->createMock(RouteMatchedHandlerContract::class);
        $dispatch  = $this->createMock(MethodDispatchContract::class);
        $parameter = $this->createMock(ParameterContract::class);
        $response  = self::createStub(ResponseContract::class);

        $entityClass = EntityClass::class;
        $cast        = new Cast(type: $entityClass);

        $parameter
            ->expects($this->once())
            ->method('getName')
            ->willReturn('user');

        $parameter
            ->expects($this->once())
            ->method('getCast')
            ->willReturn($cast);

        $route
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([$parameter]);

        $route
            ->expects($this->once())
            ->method('getDispatch')
            ->willReturn($dispatch);

        $dispatch
            ->expects($this->once())
            ->method('getArguments')
            ->willReturn(['user' => '']);

        $dispatch
            ->expects($this->once())
            ->method('getDependencies')
            ->willReturn(['user' => $entityClass]);

        $this->responseFactory
            ->expects($this->once())
            ->method('createResponseFromView')
            ->with('errors/400', null, StatusCode::BAD_REQUEST, null)
            ->willReturn($response);

        $handler
            ->expects($this->never())
            ->method('routeMatched');

        $result = $this->middleware->routeMatched($request, $route, $handler);

        self::assertInstanceOf(ResponseContract::class, $result);
    }

    public function testRouteMatchedWithEntityCastAndCustomColumn(): void
    {
        $this->container->expects($this->never())->method('get');
        $this->responseFactory->expects($this->never())->method('createResponseFromView');

        $request    = self::createStub(ServerRequestContract::class);
        $route      = $this->createMock(RouteContract::class);
        $handler    = $this->createMock(RouteMatchedHandlerContract::class);
        $dispatch   = $this->createMock(MethodDispatchContract::class);
        $parameter  = $this->createMock(ParameterContract::class);
        $repository = $this->createMock(RepositoryContract::class);
        $entity     = self::createStub(EntityContract::class);

        $entityClass = EntityClass::class;
        $cast        = new EntityCast(type: $entityClass, column: 'slug');

        $parameter
            ->expects($this->once())
            ->method('getName')
            ->willReturn('user');

        $parameter
            ->expects($this->exactly(2))
            ->method('getCast')
            ->willReturn($cast);

        $route
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([$parameter]);

        $route
            ->expects($this->once())
            ->method('getDispatch')
            ->willReturn($dispatch);

        $route
            ->expects($this->once())
            ->method('withDispatch')
            ->willReturnSelf();

        $dispatch
            ->expects($this->once())
            ->method('getArguments')
            ->willReturn(['user' => 'john-doe']);

        $dispatch
            ->expects($this->once())
            ->method('getDependencies')
            ->willReturn(['user' => $entityClass]);

        $dispatch
            ->expects($this->once())
            ->method('withDependencies')
            ->willReturnSelf();

        $dispatch
            ->expects($this->once())
            ->method('withArguments')
            ->willReturnSelf();

        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->with($entityClass)
            ->willReturn($repository);

        $repository
            ->expects($this->once())
            ->method('findBy')
            ->willReturn($entity);

        $handler
            ->expects($this->once())
            ->method('routeMatched')
            ->willReturn($route);

        $result = $this->middleware->routeMatched($request, $route, $handler);

        self::assertInstanceOf(RouteContract::class, $result);
    }

    public function testRouteMatchedWithEntityFoundById(): void
    {
        $this->container->expects($this->never())->method('get');
        $this->responseFactory->expects($this->never())->method('createResponseFromView');

        $request    = self::createStub(ServerRequestContract::class);
        $route      = $this->createMock(RouteContract::class);
        $handler    = $this->createMock(RouteMatchedHandlerContract::class);
        $dispatch   = $this->createMock(MethodDispatchContract::class);
        $parameter  = $this->createMock(ParameterContract::class);
        $repository = $this->createMock(RepositoryContract::class);
        $entity     = self::createStub(EntityContract::class);

        $entityClass = EntityClass::class;
        $cast        = new Cast(type: $entityClass);

        $parameter
            ->expects($this->once())
            ->method('getName')
            ->willReturn('user');

        $parameter
            ->expects($this->exactly(2))
            ->method('getCast')
            ->willReturn($cast);

        $route
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([$parameter]);

        $route
            ->expects($this->once())
            ->method('getDispatch')
            ->willReturn($dispatch);

        $route
            ->expects($this->once())
            ->method('withDispatch')
            ->willReturnSelf();

        $dispatch
            ->expects($this->once())
            ->method('getArguments')
            ->willReturn(['user' => 42]);

        $dispatch
            ->expects($this->once())
            ->method('getDependencies')
            ->willReturn(['user' => $entityClass]);

        $dispatch
            ->expects($this->once())
            ->method('withDependencies')
            ->willReturnSelf();

        $dispatch
            ->expects($this->once())
            ->method('withArguments')
            ->willReturnSelf();

        $this->orm
            ->expects($this->once())
            ->method('createRepository')
            ->with($entityClass)
            ->willReturn($repository);

        $repository
            ->expects($this->once())
            ->method('find')
            ->with(42)
            ->willReturn($entity);

        $handler
            ->expects($this->once())
            ->method('routeMatched')
            ->willReturn($route);

        $result = $this->middleware->routeMatched($request, $route, $handler);

        self::assertInstanceOf(RouteContract::class, $result);
    }
}
