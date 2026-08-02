<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Struct\Response\Contract;

use Valkyrja\Http\Struct\Contract\StructContract;

interface ResponseStructContract extends StructContract
{
    /**
     * @param array<string, mixed> $data       The data to structure
     * @param bool                 $includeAll [optional] Whether to include all values including items non-existent in $data
     *
     * @return array<string|int, mixed>
     */
    public static function getStructuredData(array $data, bool $includeAll = true): array;
}
