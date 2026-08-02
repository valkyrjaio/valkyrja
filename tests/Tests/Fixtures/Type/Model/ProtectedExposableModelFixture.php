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

use Valkyrja\Tests\Fixtures\Type\Model\Trait\PrivatePropertyTrait;
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Contract\ExposableModelContract;
use Valkyrja\Type\Model\Trait\ProtectedExposable;

/**
 * Model class to use to test ProtectedExposable model.
 *
 * @property string $protected
 */
final class ProtectedExposableModelFixture extends Model implements ExposableModelContract
{
    use PrivatePropertyTrait;
    use ProtectedExposable;

    public string $public;

    public string|null $nullable;

    protected string $protected;

    public static function getExposable(): array
    {
        return [
            ModelFixture::PROTECTED,
            ModelFixture::PRIVATE,
        ];
    }
}
