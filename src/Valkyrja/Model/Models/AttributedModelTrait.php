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

namespace Valkyrja\Model\Models;

use Attribute;
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
     *
     * @param class-string<Attribute>|null $name [optional] The attribute name to filter by
     *
     * @return ReflectionAttribute[]
     */
    public static function getAttributes(string $name = null): array
    {
        return self::$attributes[static::class . ($name ?? '')]
            ??= Reflector::getClassReflection(static::class)->getAttributes($name);
    }
}
