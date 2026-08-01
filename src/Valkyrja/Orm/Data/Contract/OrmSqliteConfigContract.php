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

interface OrmSqliteConfigContract
{
    /** @var non-empty-string */
    public string $sqliteDb {
        get;
    }

    /** @var non-empty-string */
    public string $sqliteHost {
        get;
    }

    /** @var positive-int */
    public int $sqlitePort {
        get;
    }

    /** @var non-empty-string */
    public string $sqliteUser {
        get;
    }

    /** @var non-empty-string */
    public string $sqlitePassword {
        get;
    }

    /** @var non-empty-string */
    public string $sqliteCharset {
        get;
    }

    /** @var array<int, int|bool> */
    public array $sqliteOptions {
        get;
    }
}
