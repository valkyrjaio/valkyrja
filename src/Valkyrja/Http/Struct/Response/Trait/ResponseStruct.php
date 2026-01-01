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

namespace Valkyrja\Http\Struct\Response\Trait;

use Valkyrja\Type\BuiltIn\Enum\Trait\Arrayable;

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
