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

namespace Valkyrja\Validation\Rule\Orm;

use Override;
use Valkyrja\Validation\Rule\Orm\Abstract\EntityRule;

class EntityNotExists extends EntityRule
{
    #[Override]
    public function isValid(): bool
    {
        return $this->checkForEntity() === null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function getDefaultErrorMessage(): string
    {
        return 'The entity does exist';
    }
}
