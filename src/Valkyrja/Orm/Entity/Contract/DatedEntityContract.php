<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Entity\Contract;

/**
 * The contract declares that the repository stamps a created date and a
 * modified date on the entity.
 *
 * The contract holds no method. A data object holds no static method, so the
 * date format and the two field names live in the entity metadata registry.
 * The repository throws when an entity implements this contract and the
 * registry holds no dated metadata for the entity.
 *
 * @see \Valkyrja\Orm\Data\DatedMetadata
 * @see \Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract
 */
interface DatedEntityContract extends EntityContract
{
}
