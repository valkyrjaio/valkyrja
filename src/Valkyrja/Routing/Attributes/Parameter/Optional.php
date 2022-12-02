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

namespace Valkyrja\Routing\Attributes\Parameter;

use Attribute;
use BackedEnum;
use Valkyrja\ORM\Entity;
use Valkyrja\Routing\Attributes\Parameter;
use Valkyrja\Routing\Constants\Regex;
use Valkyrja\Routing\Enums\CastType;

/**
 * Attribute Parameter.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Optional extends Parameter
{
    /**
     * @param class-string<Entity>|null     $entity
     * @param class-string<BackedEnum>|null $enum
     */
    public function __construct(
        string $name,
        string $regex = null,
        CastType $type = null,
        string $entity = null,
        string $entityColumn = null,
        array $entityRelationships = null,
        string $enum = null,
        bool $shouldCapture = true,
        mixed $default = null,
    ) {
        parent::__construct(
            name               : $name,
            regex              : $regex ?? Regex::ANY,
            type               : $type,
            entity             : $entity,
            entityColumn       : $entityColumn,
            entityRelationships: $entityRelationships,
            enum               : $enum,
            shouldCapture      : $shouldCapture,
            default            : $default,
        );
    }
}
