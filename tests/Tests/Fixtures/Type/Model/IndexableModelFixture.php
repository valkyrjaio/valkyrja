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
use Valkyrja\Type\Model\Contract\IndexedModelContract;
use Valkyrja\Type\Model\Trait\Indexable;

/**
 * Model class to use to test Indexable model.
 *
 * @property string $protected
 */
final class IndexableModelFixture extends Model implements IndexedModelContract
{
    use Indexable;
    use PrivatePropertyTrait;

    public const PUBLIC_INDEX    = 1;
    public const PROTECTED_INDEX = 2;
    public const PRIVATE_INDEX   = 3;
    public const NULLABLE_INDEX  = 4;

    public string $public;

    public string|null $nullable;

    protected string $protected;

    /**
     * @inheritDoc
     */
    public static function getIndexes(): array
    {
        return [
            ModelFixture::PUBLIC    => 1,
            ModelFixture::PROTECTED => 2,
            ModelFixture::PRIVATE   => 3,
            ModelFixture::NULLABLE  => 4,
        ];
    }
}
