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

namespace Valkyrja\Orm\Data\Contract;

interface OrmMysqlConfigContract
{
    /** @var non-empty-string */
    public string $mysqlDb {
        get;
    }

    /** @var non-empty-string */
    public string $mysqlHost {
        get;
    }

    /** @var positive-int */
    public int $mysqlPort {
        get;
    }

    /** @var non-empty-string */
    public string $mysqlUser {
        get;
    }

    /** @var non-empty-string */
    public string $mysqlPassword {
        get;
    }

    /** @var non-empty-string */
    public string $mysqlCharset {
        get;
    }

    public bool|null $mysqlStrict {
        get;
    }

    /** @var non-empty-string|null */
    public string|null $mysqlEngine {
        get;
    }

    /** @var array<int, int|bool> */
    public array $mysqlOptions {
        get;
    }
}
