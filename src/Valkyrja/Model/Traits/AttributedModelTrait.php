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

namespace Valkyrja\Model\Traits;

use ReflectionAttribute;
use Valkyrja\Reflection\Facades\Reflector;

/**
 * Trait AttributedModelTrait.
 *
 * @author Melech Mizrachi
 */
trait AttributedModelTrait
{
    /**
     * @var array<string, ReflectionAttribute[]>
     */
    protected static array $attributes = [];

    /**
     * @inheritDoc
     */
    public static function getAttributes(string $name = null): array
    {
        $name = static::class . ($name ?? '');

        return self::$attributes[$name]
            ?? self::$attributes[$name] = Reflector::getClassReflection(static::class)->getAttributes($name);
    }
}
