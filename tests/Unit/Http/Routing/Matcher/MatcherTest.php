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

namespace Valkyrja\Tests\Unit\Http\Routing\Matcher;

use Override;
use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Http\Message\Factory\ResponseFactory;
use Valkyrja\Http\Routing\Collection\Collection;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Exception\InvalidRoutePathException;
use Valkyrja\Http\Routing\Matcher\Matcher;
use Valkyrja\Tests\Classes\Http\Routing\Controller\ControllerClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\IntT;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Enum\CastType;

/**
 * Test the Matcher service.
 *
 * @author Melech Mizrachi
 */
class MatcherTest extends TestCase
{
    protected const string STATIC_PATH = '/';
    protected const string STATIC_NAME = 'static';

    protected const string DYNAMIC_PATH  = '/dynamic';
    protected const string DYNAMIC_NAME  = 'dynamic';
    protected const string DYNAMIC_REGEX = '/^\/([a-zA-Z]+)$/';

    protected const string OPTIONAL_DYNAMIC_PATH  = '/optional';
    protected const string OPTIONAL_DYNAMIC_NAME  = 'optional-dynamic';
    protected const string OPTIONAL_DYNAMIC_REGEX = '/^\/optional(?:\/)?([a-zA-Z]+)?$/';

    protected const string CAST_DYNAMIC_PATH  = '/cast/2/235';
    protected const string CAST_DYNAMIC_NAME  = 'cast-dynamic';
    protected const string CAST_DYNAMIC_REGEX = '/^\/cast\/(\d+)\/(\d+)$/';

    protected const string INVALID_DYNAMIC_PATH  = '/invalid/dynamic';
    protected const string INVALID_DYNAMIC_NAME  = 'invalid-dynamic';
    protected const string INVALID_DYNAMIC_REGEX = '/^\/invalid\/([a-zA-Z]+)$/';

    protected Matcher $matcher;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $route = new Route(
            path: self::STATIC_PATH,
            name: self::STATIC_NAME,
            dispatch: new MethodDispatch(
                class: ControllerClass::class,
                method: 'parameters',
                dependencies: [
                    ResponseFactory::class,
                ]
            ),
        );

        $dynamicRoute = new Route(
            path: self::DYNAMIC_PATH,
            name: self::DYNAMIC_NAME,
            dispatch: new MethodDispatch(
                class: ControllerClass::class,
                method: 'parameters',
                dependencies: [
                    ResponseFactory::class,
                ]
            ),
            regex: self::DYNAMIC_REGEX,
            parameters: [
                new Parameter(
                    name: self::DYNAMIC_NAME,
                    regex: Regex::ALPHA
                ),
            ]
        );

        $optionalDynamicRoute = new Route(
            path: self::OPTIONAL_DYNAMIC_PATH,
            name: self::OPTIONAL_DYNAMIC_NAME,
            dispatch: new MethodDispatch(
                class: ControllerClass::class,
                method: 'parameters',
                dependencies: [
                    ResponseFactory::class,
                ]
            ),
            regex: self::OPTIONAL_DYNAMIC_REGEX,
            parameters: [
                new Parameter(
                    name: self::DYNAMIC_NAME,
                    regex: Regex::ALPHA,
                    isOptional: true,
                    default: 'default'
                ),
            ]
        );

        $castDynamicRoute = new Route(
            path: self::CAST_DYNAMIC_PATH,
            name: self::CAST_DYNAMIC_NAME,
            dispatch: new MethodDispatch(
                class: ControllerClass::class,
                method: 'parameters',
                dependencies: [
                    ResponseFactory::class,
                ]
            ),
            regex: self::CAST_DYNAMIC_REGEX,
            parameters: [
                new Parameter(
                    name: self::DYNAMIC_NAME,
                    regex: Regex::NUM,
                    cast: new Cast(
                        type: CastType::int,
                        convert: true,
                    ),
                ),
                new Parameter(
                    name: self::DYNAMIC_NAME,
                    regex: Regex::NUM,
                    cast: new Cast(
                        type: CastType::int,
                        convert: false,
                    ),
                ),
            ]
        );

        $invalidDynamicRoute = new Route(
            path: self::INVALID_DYNAMIC_PATH,
            name: self::INVALID_DYNAMIC_NAME,
            dispatch: new MethodDispatch(
                class: ControllerClass::class,
                method: 'parameters',
                dependencies: [
                    ResponseFactory::class,
                ]
            ),
            regex: self::INVALID_DYNAMIC_REGEX,
        );

        $collection = new Collection();
        $collection->add($route);
        $collection->add($castDynamicRoute);
        $collection->add($optionalDynamicRoute);
        $collection->add($invalidDynamicRoute);
        $collection->add($dynamicRoute);

        $this->matcher = new Matcher(collection: $collection);
    }

    public function testNoMatch(): void
    {
        $path        = self::STATIC_PATH;
        $dynamicPath = self::DYNAMIC_PATH;

        $matcher = new Matcher();

        self::assertNull($matcher->match($path));
        self::assertNull($matcher->match($dynamicPath));
        self::assertNull($matcher->matchStatic($path));
        self::assertNull($matcher->matchStatic($dynamicPath));
        self::assertNull($matcher->matchDynamic($path));
        self::assertNull($matcher->matchDynamic($dynamicPath));
    }

    public function testStaticMatch(): void
    {
        $path        = self::STATIC_PATH;
        $dynamicPath = self::DYNAMIC_PATH;

        $matcher = $this->matcher;

        $route = $matcher->match($path);

        self::assertNotNull($route);
        self::assertNotNull($matcher->matchStatic($path));
        self::assertNull($matcher->matchStatic($dynamicPath));

        $arguments = $route->getDispatch()->getArguments();

        self::assertEmpty($arguments);
    }

    public function testDynamicMatch(): void
    {
        $path        = self::STATIC_PATH;
        $dynamicPath = self::DYNAMIC_PATH;

        $matcher = $this->matcher;

        $route = $matcher->match($dynamicPath);

        self::assertNotNull($route);
        self::assertNull($matcher->matchDynamic($path));
        self::assertNotNull($matcher->matchDynamic($dynamicPath));

        $arguments = $route->getDispatch()->getArguments();

        self::assertNotEmpty($arguments);
        self::assertIsString($arguments[0]);
        self::assertSame('dynamic', $arguments[0]);
    }

    public function testOptionalDynamicMatch(): void
    {
        $dynamicPath = self::OPTIONAL_DYNAMIC_PATH;

        $matcher = $this->matcher;

        $route = $matcher->match($dynamicPath);

        self::assertNotNull($route);
        self::assertNotNull($matcher->matchDynamic($dynamicPath));

        $arguments = $route->getDispatch()->getArguments();

        self::assertNotEmpty($arguments);
        self::assertIsString($arguments[0]);
        self::assertSame('default', $arguments[0]);

        $route2 = $matcher->match($dynamicPath . '/optionalvalue');

        self::assertNotNull($route2);
        self::assertNotNull($matcher->matchDynamic($dynamicPath . '/optionalvalue'));

        $arguments2 = $route2->getDispatch()->getArguments();

        self::assertNotEmpty($arguments2);
        self::assertIsString($arguments2[0]);
        self::assertSame('optionalvalue', $arguments2[0]);
    }

    public function testCastDynamicMatch(): void
    {
        $dynamicPath = self::CAST_DYNAMIC_PATH;

        $matcher = $this->matcher;

        $route = $matcher->match($dynamicPath);

        self::assertNotNull($route);
        self::assertNotNull($matcher->matchDynamic($dynamicPath));

        $arguments = $route->getDispatch()->getArguments();

        self::assertNotEmpty($arguments);
        self::assertIsInt($arguments[0]);
        self::assertInstanceOf(IntT::class, $arguments[1]);
        self::assertIsInt($arguments[1]->asValue());
    }

    public function testInvalidDynamicMatch(): void
    {
        $this->expectException(InvalidRoutePathException::class);

        $dynamicPath = self::INVALID_DYNAMIC_PATH;

        $matcher = $this->matcher;

        $matcher->match($dynamicPath);
    }
}
