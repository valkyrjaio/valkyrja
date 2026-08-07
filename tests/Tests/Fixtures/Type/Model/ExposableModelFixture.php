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

use Override;
use Valkyrja\Tests\Fixtures\Type\Model\Trait\PrivatePropertyTrait;
use Valkyrja\Type\Model\Abstract\Model;
use Valkyrja\Type\Model\Contract\ExposableModelContract;
use Valkyrja\Type\Model\Trait\Exposable;

/**
 * Model class to use to test Exposable model.
 *
 * @property string $protected
 */
final class ExposableModelFixture extends Model implements ExposableModelContract
{
    use Exposable;
    use PrivatePropertyTrait;

    public string $public;

    public string|null $nullable;

    protected string $protected;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getExposable(): array
    {
        return [
            ModelFixture::PRIVATE,
        ];
    }
}
