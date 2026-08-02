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
use Valkyrja\Tests\Fixtures\Orm\Repository\RepositoryFixture;

/**
 * Entity class with all configurable features for testing.
 */
final class EntityWithAllFeaturesFixture extends Entity
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
    public static function getRepository(): string
    {
        return RepositoryFixture::class;
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
