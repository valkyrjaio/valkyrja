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
use Valkyrja\Routing\Attributes\Parameter\Entity;
use Valkyrja\Routing\Constants\ParameterName;
use Valkyrja\Routing\Constants\Regex;

/**
 * Attribute Id.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Id extends Entity
{
    public function __construct(
        string $entity = null,
        string $entityColumn = null,
        array $entityRelationships = null,
        string $name = null,
        bool $isOptional = null,
        bool $shouldCapture = null,
        mixed $default = null,
    ) {
        parent::__construct(
            entity             : $entity,
            entityColumn       : $entityColumn,
            entityRelationships: $entityRelationships,
            name               : $name ?? ParameterName::ID,
            regex              : Regex::ID,
            isOptional         : $isOptional,
            shouldCapture      : $shouldCapture,
            default            : $default,
        );
    }
}
