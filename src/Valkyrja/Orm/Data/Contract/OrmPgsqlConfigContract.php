<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
