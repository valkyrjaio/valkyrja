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

namespace Valkyrja\Orm\Repository;

use Valkyrja\Orm\Entity\Contract\Entity;
use Valkyrja\Orm\Repository\Contract\RelationshipRepository as Contract;

/**
 * Class RelationshipRepository.
 *
 * @author Melech Mizrachi
 *
 * @template Entity of Entity
 *
 * @extends Repository<Entity>
 */
class RelationshipRepository extends Repository implements Contract
{
    /** @use RelationshipCapableRepository<Entity> */
    use RelationshipCapableRepository;
}
