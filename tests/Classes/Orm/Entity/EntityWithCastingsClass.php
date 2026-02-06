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

namespace Valkyrja\Tests\Classes\Orm\Entity;

use Override;
use Valkyrja\Orm\Entity\Abstract\Entity;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Int\IntT;

/**
 * Entity class with castings for testing.
 */
class EntityWithCastingsClass extends Entity
{
    /** @inheritDoc */
    protected static string $tableName = 'entities_with_castings';

    public int $id;
    public string $name;

    /** @var int|IntT|null Property with IntT cast */
    public int|IntT|null $score = null;

    /** @var int[]|IntT[]|non-empty-string|null Property with IntT array cast */
    public array|string|null $scores = null;

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
