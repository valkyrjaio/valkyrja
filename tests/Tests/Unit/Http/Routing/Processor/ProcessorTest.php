<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Http\Routing\Processor;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\DynamicRoute;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;
use Valkyrja\Http\Routing\Processor\Processor;
use Valkyrja\Http\Routing\Throwable\Exception\HttpRoutingInvalidRoutePathException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use function preg_match;

/**
 * Class ProcessorTest.
 */
final class ProcessorTest extends TestCase
{
    /**
     * @return array<non-empty-string, array{non-empty-string}>
     */
    public static function parameterTypeProvider(): array
    {
        return [
            'num'                  => [Regex::NUM],
            'id'                   => [Regex::ID],
            'slug'                 => [Regex::SLUG],
            'any'                  => [Regex::ANY],
            'alpha'                => [Regex::ALPHA],
            'alpha lowercase'      => [Regex::ALPHA_LOWERCASE],
            'alpha uppercase'      => [Regex::ALPHA_UPPERCASE],
            'alpha num'            => [Regex::ALPHA_NUM],
            'alpha num underscore' => [Regex::ALPHA_NUM_UNDERSCORE],
            'uuid'                 => [Regex::UUID],
            'uuid v1'              => [Regex::UUID_V1],
            'uuid v3'              => [Regex::UUID_V3],
            'uuid v4'              => [Regex::UUID_V4],
            'uuid v5'              => [Regex::UUID_V5],
            'uuid v6'              => [Regex::UUID_V6],
            'uuid v7'              => [Regex::UUID_V7],
            'uuid v8'              => [Regex::UUID_V8],
            'ulid'                 => [Regex::ULID],
            'vlid'                 => [Regex::VLID],
            'vlid v1'              => [Regex::VLID_V1],
            'vlid v2'              => [Regex::VLID_V2],
            'vlid v3'              => [Regex::VLID_V3],
            'vlid v4'              => [Regex::VLID_V4],
        ];
    }

    /**
     * @return array<non-empty-string, array{non-empty-string, array<array{non-empty-string, non-empty-string, bool, bool}>, non-empty-string}>
     */
    public static function structuralProvider(): array
    {
        return [
            'parameter at end'          => [
                '/parameters/{name}',
                [['name', Regex::ALPHA, false, true]],
                '/^\/parameters\/(?<name>[a-zA-Z]+)$/',
            ],
            'parameter at start'        => [
                '/{name}/edit',
                [['name', Regex::ALPHA, false, true]],
                '/^\/(?<name>[a-zA-Z]+)\/edit$/',
            ],
            'parameter in middle'       => [
                '/user/{id}/edit',
                [['id', Regex::NUM, false, true]],
                '/^\/user\/(?<id>\d+)\/edit$/',
            ],
            'multiple separated params' => [
                '/a/{x}/b/{y}',
                [['x', Regex::NUM, false, true], ['y', Regex::ALPHA, false, true]],
                '/^\/a\/(?<x>\d+)\/b\/(?<y>[a-zA-Z]+)$/',
            ],
            'adjacent params'           => [
                '/{x}{y}',
                [['x', Regex::NUM, false, true], ['y', Regex::ALPHA, false, true]],
                '/^\/(?<x>\d+)(?<y>[a-zA-Z]+)$/',
            ],
        ];
    }

    /**
     * @return array<non-empty-string, array{non-empty-string, array<array{non-empty-string, non-empty-string, bool, bool}>, non-empty-string}>
     */
    public static function modifierProvider(): array
    {
        return [
            'single optional'          => [
                '/{opt?}',
                [['opt', Regex::ALPHA, true, true]],
                '/^(?:\/)?(?<opt>[a-zA-Z]+)?$/',
            ],
            'non-capture'              => [
                '/{nc}',
                [['nc', Regex::ALPHA, false, false]],
                '/^\/(?:[a-zA-Z]+)$/',
            ],
            'optional non-capture'     => [
                '/{onc?}',
                [['onc', Regex::ALPHA, true, false]],
                '/^(?:\/)?(?:[a-zA-Z]+)?$/',
            ],
            'multiple optionals'       => [
                '/{a?}/{b?}',
                [['a', Regex::ALPHA, true, true], ['b', Regex::ALPHA, true, true]],
                '/^(?:\/)?(?<a>[a-zA-Z]+)?(?:\/)?(?<b>[a-zA-Z]+)?$/',
            ],
            'mixed capture/no-capture' => [
                '/{cap}/{nc}',
                [['cap', Regex::ALPHA, false, true], ['nc', Regex::NUM, false, false]],
                '/^\/(?<cap>[a-zA-Z]+)\/(?:\d+)$/',
            ],
        ];
    }

    /**
     * Build parameters from compact specs: [name, regex, isOptional, shouldCapture].
     *
     * @param array<array{non-empty-string, non-empty-string, bool, bool}> $parameterSpecs
     *
     * @return array<Parameter>
     */
    private static function buildParameters(array $parameterSpecs): array
    {
        $parameters = [];

        foreach ($parameterSpecs as [$name, $regex, $isOptional, $shouldCapture]) {
            $parameters[] = new Parameter(
                name: $name,
                regex: $regex,
                isOptional: $isOptional,
                shouldCapture: $shouldCapture,
            );
        }

        return $parameters;
    }

    public function testStaticRoute(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/',
            name: 'route',
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
    }

    public function testStaticRouteNoPreceedingSlash(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: 'some/path',
            name: 'route',
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame('/some/path', $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
    }

    public function testDynamicRoute(): void
    {
        $processor = new Processor();

        $route = new DynamicRoute(
            path: '/{value}',
            name: 'route',
            regex: '',
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA
                ),
            ],
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^\/(?<value>[a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteInvalidPath(): void
    {
        $this->expectException(HttpRoutingInvalidRoutePathException::class);

        $processor = new Processor();

        $route = new DynamicRoute(
            path: '/{val}',
            name: 'route',
            regex: '',
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA
                ),
            ],
            handler: static fn (): null => null,
        );

        $processor->route($route);
    }

    public function testDynamicRouteWithRegexAlreadySet(): void
    {
        $processor = new Processor();

        $route = new DynamicRoute(
            path: '/{value}',
            name: 'route',
            regex: Regex::ALPHA,
            parameters: [
                new Parameter(
                    name: 'value',
                    regex: Regex::ALPHA
                ),
            ],
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        // Shouldn't change, even if it's wrong
        self::assertSame($route->getRegex(), $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteWithOptionalParam(): void
    {
        $processor = new Processor();

        $route = new DynamicRoute(
            path: '/{optional?}',
            name: 'route',
            regex: '',
            parameters: [
                new Parameter(
                    name: 'optional',
                    regex: Regex::ALPHA,
                    isOptional: true
                ),
            ],
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^(?:\/)?(?<optional>[a-zA-Z]+)?$/', $routeAfterProcessing->getRegex());
    }

    public function testDynamicRouteWithNonCaptureParam(): void
    {
        $processor = new Processor();

        $route = new DynamicRoute(
            path: '/{noncapture}',
            name: 'route',
            regex: '',
            parameters: [
                new Parameter(
                    name: 'noncapture',
                    regex: Regex::ALPHA,
                    shouldCapture: false
                ),
            ],
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertSame($route->getPath(), $routeAfterProcessing->getPath());
        self::assertSame($route->getName(), $routeAfterProcessing->getName());
        self::assertSame('/^\/(?:[a-zA-Z]+)$/', $routeAfterProcessing->getRegex());
    }

    /**
     * Every parameter regex type flows through unchanged, wrapped in a named capture group,
     * and the produced regex is valid PCRE.
     *
     * @param non-empty-string $typeRegex
     */
    #[DataProvider('parameterTypeProvider')]
    public function testCapturingParameterTypeProducesExpectedRegex(string $typeRegex): void
    {
        $regex = $this->processRegex(
            path: '/{id}',
            parameters: [new Parameter(name: 'id', regex: $typeRegex)],
        );

        self::assertSame('/^\/(?<id>' . $typeRegex . ')$/', $regex);
        // The produced regex must be a syntactically valid PCRE (no escaping/delimiter issues).
        self::assertNotFalse(@preg_match($regex, ''));
    }

    /**
     * Structural combinations: parameter position, multiple/adjacent parameters, static mixes.
     *
     * @param non-empty-string                                             $path
     * @param array<array{non-empty-string, non-empty-string, bool, bool}> $parameterSpecs
     * @param non-empty-string                                             $expected
     */
    #[DataProvider('structuralProvider')]
    public function testStructuralCombinationProducesExpectedRegex(string $path, array $parameterSpecs, string $expected): void
    {
        $regex = $this->processRegex(
            path: $path,
            parameters: self::buildParameters($parameterSpecs),
        );

        self::assertSame($expected, $regex);
        self::assertNotFalse(@preg_match($regex, ''));
    }

    /**
     * Modifier combinations: optional, non-capture, and their mixes.
     *
     * @param non-empty-string                                             $path
     * @param array<array{non-empty-string, non-empty-string, bool, bool}> $parameterSpecs
     * @param non-empty-string                                             $expected
     */
    #[DataProvider('modifierProvider')]
    public function testModifierCombinationProducesExpectedRegex(string $path, array $parameterSpecs, string $expected): void
    {
        $regex = $this->processRegex(
            path: $path,
            parameters: self::buildParameters($parameterSpecs),
        );

        self::assertSame($expected, $regex);
        self::assertNotFalse(@preg_match($regex, ''));
    }

    /**
     * A path marking a parameter optional via `{name?}` flips a non-optional parameter to
     * optional when building the regex (the `str_contains($regex, name . '?')` branch).
     */
    public function testPathQuestionMarkForcesOptionalRegex(): void
    {
        $regex = $this->processRegex(
            path: '/{opt?}',
            // Constructed as NOT optional; the '?' in the path must still make the regex optional.
            parameters: [new Parameter(name: 'opt', regex: Regex::ALPHA, isOptional: false)],
        );

        self::assertSame('/^(?:\/)?(?<opt>[a-zA-Z]+)?$/', $regex);
    }

    /**
     * A dynamic route whose path has no `{` placeholder is left untouched (no regex built).
     */
    public function testDynamicRouteWithoutPlaceholderIsNotGivenRegex(): void
    {
        $processor = new Processor();

        $route = new DynamicRoute(
            path: '/static/path',
            name: 'route',
            regex: '',
            parameters: [],
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertInstanceOf(DynamicRoute::class, $routeAfterProcessing);
        self::assertSame('/static/path', $routeAfterProcessing->getPath());
        self::assertSame('', $routeAfterProcessing->getRegex());
    }

    /**
     * A non-dynamic route containing `{` in its path is not treated as dynamic (no regex).
     */
    public function testNonDynamicRouteWithBraceInPathIsLeftAsStatic(): void
    {
        $processor = new Processor();

        $route = new Route(
            path: '/{notDynamic}',
            name: 'route',
            handler: static fn (): null => null,
        );

        $routeAfterProcessing = $processor->route($route);

        self::assertNotInstanceOf(DynamicRoute::class, $routeAfterProcessing);
        self::assertSame('/{notDynamic}', $routeAfterProcessing->getPath());
    }

    /**
     * Build the produced regex for a dynamic route from a path and its parameters.
     *
     * @param non-empty-string $path
     * @param array<Parameter> $parameters
     *
     * @throws HttpRoutingInvalidRoutePathException
     */
    private function processRegex(string $path, array $parameters): string
    {
        $route = new DynamicRoute(
            path: $path,
            name: 'route',
            regex: '',
            parameters: $parameters,
            handler: static fn (): null => null,
        );

        $processed = new Processor()->route($route);

        self::assertInstanceOf(DynamicRoute::class, $processed);

        return $processed->getRegex();
    }
}
