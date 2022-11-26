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
use Valkyrja\Routing\Attributes\Parameter;
use Valkyrja\Routing\Constants\Regex;

/**
 * Attribute Num.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Num extends Parameter
{
    public function __construct(
        string $name = null,
        string $entity = null,
        string $entityColumn = null,
        array $entityRelationships = null,
        bool $isOptional = null,
        bool $shouldCapture = null,
        mixed $default = null,
    ) {
        parent::__construct(
            name               : $name ?? 'num',
            regex              : Regex::NUM,
            entity             : $entity,
            entityColumn       : $entityColumn,
            entityRelationships: $entityRelationships,
            isOptional         : $isOptional,
            shouldCapture      : $shouldCapture,
            default            : $default,
        );
    }
}
