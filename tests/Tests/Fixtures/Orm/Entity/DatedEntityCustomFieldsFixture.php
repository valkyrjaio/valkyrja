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

namespace Valkyrja\Tests\Fixtures\Orm\Entity;

use Override;
use Valkyrja\Orm\Entity\Abstract\Entity;
use Valkyrja\Orm\Entity\Contract\DatedEntityContract;

/**
 * Entity to use to test the date stamping with a field that is not the default.
 */
final class DatedEntityCustomFieldsFixture extends Entity implements DatedEntityContract
{
    public int $id;
    public string $name;
    public string $created_at;
    public string $updated_at;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getTableName(): string
    {
        return 'test';
    }

    public function setId(string|int $id): void
    {
        $this->id = (int) $id;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function internalSetCallables(): array
    {
        return [
            'id' => [$this, 'setId'],
        ];
    }
}
