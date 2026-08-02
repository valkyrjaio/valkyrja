<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Orm\Entity;

use Override;
use Valkyrja\Orm\Entity\Abstract\Entity;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Int\IntT;

/**
 * Entity class with castings for testing.
 */
final class EntityWithCastingsFixture extends Entity
{
    public int $id;
    public string $name;

    /** @var int|IntT|null Property with IntT cast */
    public int|IntT|null $score = null;

    /** @var int[]|IntT[]|non-empty-string|null Property with IntT array cast */
    public array|string|null $scores = null;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getTableName(): string
    {
        return 'entities_with_castings';
    }

    /**
     * @inheritDoc
     *
     * @return array<string, Cast>
     */
    #[Override]
    public static function getCastings(): array
    {
        return [
            'score'  => new Cast(IntT::class),
            'scores' => new Cast(IntT::class, isArray: true),
        ];
    }
}
