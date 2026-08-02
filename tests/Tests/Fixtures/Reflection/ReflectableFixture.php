<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Reflection;

use Valkyrja\Container\Manager\Contract\ContainerContract;

/**
 * Class to test reflections.
 */
final class ReflectableFixture
{
    public const string STRING = 'const-string';

    public static string $string = 'static-string-property';

    public string $property = 'property';

    public static function testStatic(): string
    {
        return 'method-static';
    }

    public function test(ContainerContract $container): string
    {
        return 'method';
    }
}
