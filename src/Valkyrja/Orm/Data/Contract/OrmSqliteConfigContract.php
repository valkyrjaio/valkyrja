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
