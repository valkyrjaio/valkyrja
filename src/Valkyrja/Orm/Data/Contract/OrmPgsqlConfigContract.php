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

interface OrmPgsqlConfigContract
{
    /** @var non-empty-string */
    public string $pgsqlDb {
        get;
    }

    /** @var non-empty-string */
    public string $pgsqlHost {
        get;
    }

    /** @var positive-int */
    public int $pgsqlPort {
        get;
    }

    /** @var non-empty-string */
    public string $pgsqlUser {
        get;
    }

    /** @var non-empty-string */
    public string $pgsqlPassword {
        get;
    }

    /** @var non-empty-string */
    public string $pgsqlCharset {
        get;
    }

    /** @var non-empty-string */
    public string $pgsqlSchema {
        get;
    }

    /** @var non-empty-string */
    public string $pgsqlSslMode {
        get;
    }

    /** @var array<int, int|bool> */
    public array $pgsqlOptions {
        get;
    }
}
