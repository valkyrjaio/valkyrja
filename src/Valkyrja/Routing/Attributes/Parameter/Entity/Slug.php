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

namespace Valkyrja\Routing\Attributes\Parameter\Entity;

use Attribute;
use Valkyrja\ORM\Entity;
use Valkyrja\Routing\Attributes\Parameter\Slug as Parameter;
use Valkyrja\Routing\Enums\CastType;

/**
 * Attribute Slug.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Slug extends Parameter
{
    /**
     * @param class-string<Entity>|null $entity
     */
    public function __construct(
        string $entity = null,
        string $entityColumn = null,
        array $entityRelationships = null,
        string $name = null,
        bool $isOptional = false,
        bool $shouldCapture = true,
        mixed $default = null,
    ) {
        parent::__construct(
            name               : $name,
            type               : CastType::entity,
            entity             : $entity,
            entityColumn       : $entityColumn,
            entityRelationships: $entityRelationships,
            isOptional         : $isOptional,
            shouldCapture      : $shouldCapture,
            default            : $default,
        );
    }
}
