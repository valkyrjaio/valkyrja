<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Type\Model;

use Closure;
use Override;
use RuntimeException;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Model\Abstract\CastableModel;

use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Model class to use to test Castable model.
 */
final class EmptyCastableModelFixture extends CastableModel
{
    #[Override]
    protected function internalSetProperties(array $properties, Closure|null $modifyValue = null): void
    {
        parent::internalSetProperties(
            $properties,
            static function (string $property, mixed $value, Cast|null $cast): mixed {
                throw new RuntimeException(json_encode([$property, $value, $cast], JSON_THROW_ON_ERROR));
            }
        );
    }
}
