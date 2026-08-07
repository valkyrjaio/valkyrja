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
use Valkyrja\Type\Model\Contract\ExposableIndexedModelContract;
use Valkyrja\Type\Model\Trait\ExposableIndexable;

/**
 * Model class to use to test Indexable model.
 *
 * @property string $protected
 */
final class ExposedIndexableModelFixture extends Model implements ExposableIndexedModelContract
{
    use ExposableIndexable;
    use PrivatePropertyTrait;

    public string $public;

    public string|null $nullable;

    protected string $protected;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getIndexes(): array
    {
        return IndexableModelFixture::getIndexes();
    }

    /**
     * @inheritDoc
     *
     * @return string[]
     */
    #[Override]
    public static function getExposable(): array
    {
        return ExposableModelFixture::getExposable();
    }
}
