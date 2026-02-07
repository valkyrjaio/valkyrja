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

namespace Valkyrja\Tests\Classes\Orm\Entity;

use Override;
use Valkyrja\Orm\Entity\Abstract\Entity;

/**
 * Model class to use to test abstract model.
 */
final class EntityStringIdClass extends Entity
{
    public string $id;
    public string $name;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getTableName(): string
    {
        return 'test';
    }
}
