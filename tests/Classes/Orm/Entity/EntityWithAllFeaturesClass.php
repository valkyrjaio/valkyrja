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

use Valkyrja\Orm\Entity\Abstract\Entity;
use Valkyrja\Orm\Repository\Repository;

/**
 * Entity class with all configurable features for testing.
 */
class EntityWithAllFeaturesClass extends Entity
{
    /** @inheritDoc */
    protected static string $tableName = 'entities_with_features';

    /** @inheritDoc */
    protected static string $idField = 'entity_id';

    /** @inheritDoc */
    protected static string|null $repository = Repository::class;

    /** @inheritDoc */
    protected static array $relationshipProperties = ['relatedEntity'];

    /** @inheritDoc */
    protected static array $unStorableFields = ['tempField'];

    public int $entity_id;
    public string $name;
    public string|null $description = null;
    public string|null $tempField   = null;
    public mixed $relatedEntity     = null;
}
