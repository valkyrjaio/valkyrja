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
use Valkyrja\Orm\Entity;
use Valkyrja\Routing\Attributes\Parameter;
use Valkyrja\Routing\Constants\ParameterName;
use Valkyrja\Routing\Constants\Regex;
use Valkyrja\Routing\Enums\CastType;

/**
 * Attribute Vlid.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Vlid extends Parameter
{
    /**
     * @param class-string<Entity>|null     $entity
     * @param class-string<BackedEnum>|null $enum
     */
    public function __construct(
        string|null $name = null,
        CastType|null $type = null,
        string|null $entity = null,
        string|null $entityColumn = null,
        array|null $entityRelationships = null,
        string|null $enum = null,
        bool $isOptional = false,
        bool $shouldCapture = true,
        mixed $default = null,
    ) {
        parent::__construct(
            name               : $name ?? ParameterName::VLID,
            regex              : Regex::VLID,
            type               : $type,
            entity             : $entity,
            entityColumn       : $entityColumn,
            entityRelationships: $entityRelationships,
            enum               : $enum,
            isOptional         : $isOptional,
            shouldCapture      : $shouldCapture,
            default            : $default,
        );
    }
}
