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
use Valkyrja\Orm\Repository\Repository;

/**
 * Entity class with all configurable features for testing.
 */
final class EntityWithAllFeaturesClass extends Entity
{
    public int $entity_id;
    public string $name;
    public string|null $description   = null;
    public string|null $tempField     = null;
    public mixed $relatedEntity       = null;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getTableName(): string
    {
        return 'entities_with_features';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getIdField(): string
    {
        return 'entity_id';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getRepository(): string|null
    {
        return Repository::class;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getRelationshipProperties(): array
    {
        return ['relatedEntity'];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getUnStorableFields(): array
    {
        return ['tempField'];
    }
}
