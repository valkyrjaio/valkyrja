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

use Valkyrja\Orm\RelationshipRepository as Contract;

/**
 * Class RelationshipRepository.
 *
 * @author Melech Mizrachi
 */
class RelationshipRepository extends Repository implements Contract
{
    use RelationshipCapableRepository;
}
