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
 * The contract declares that the repository soft deletes the entity. The
 * repository stamps a deleted date instead of a `DELETE` statement, and
 * `forceDelete()` removes the row.
 *
 * The contract holds no method. A data object holds no static method, so the
 * date format and the field name live in the entity metadata registry. The
 * repository throws when an entity implements this contract and the registry
 * holds no soft delete metadata for the entity.
 *
 * @see \Valkyrja\Orm\Data\SoftDeleteMetadata
 * @see \Valkyrja\Orm\Registry\Contract\EntityMetadataRegistryContract
 */
interface SoftDeleteEntityContract extends EntityContract
{
}
