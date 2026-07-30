<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Struct\Response\Trait;

use Valkyrja\Type\Enum\Trait\Arrayable;

use function array_key_exists;

trait ResponseStruct
{
    use Arrayable;

    /**
     * @inheritDoc
     */
    public static function getStructuredData(array $data, bool $includeAll = true): array
    {
        $asArray    = self::asArray();
        $structured = [];

        foreach ($asArray as $key => $value) {
            if (! $includeAll && ! array_key_exists($key, $data)) {
                continue;
            }

            $structured[$value] = $data[$key] ?? null;
        }

        return $structured;
    }
}
