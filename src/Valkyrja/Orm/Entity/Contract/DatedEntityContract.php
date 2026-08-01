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
