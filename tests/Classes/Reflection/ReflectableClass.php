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

namespace Valkyrja\Tests\Classes\Reflection;

use Valkyrja\Container\Manager\Contract\Container;

/**
 * Class to test reflections.
 *
 * @author Melech Mizrachi
 */
class ReflectableClass
{
    public const string STRING = 'const-string';

    public static string $string = 'static-string-property';

    public string $property = 'property';

    public static function testStatic(): string
    {
        return 'method-static';
    }

    public function test(Container $container): string
    {
        return 'method';
    }
}
