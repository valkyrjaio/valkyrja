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

namespace Valkyrja\Routing\Attributes;

use Attribute;
use Valkyrja\Routing\Models\Parameter as Model;

/**
 * Attribute Parameter.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Parameter extends Model
{
    public function __construct(
        string $name = null,
        string $regex = null,
        string $type = null,
        string $entity = null,
        string $entityColumn = null,
        array $entityRelationships = null,
        bool $isOptional = null,
        bool $shouldCapture = null,
    ) {
        if ($name) {
            $this->name = $name;
        }

        if ($regex) {
            $this->regex = $regex;
        }

        if ($type) {
            $this->type = $type;
        }

        if ($entity) {
            $this->entity = $entity;
        }

        if ($entityColumn) {
            $this->entityColumn = $entityColumn;
        }

        if ($entityRelationships) {
            $this->entityRelationships = $entityRelationships;
        }

        if ($isOptional) {
            $this->isOptional = $isOptional;
        }

        if ($shouldCapture) {
            $this->shouldCapture = $shouldCapture;
        }
    }
}
