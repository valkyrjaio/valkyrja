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

namespace Valkyrja\Http\Struct\Response\Contract;

use Valkyrja\Http\Struct\Contract\StructContract;

/**
 * Interface ResponseStructContract.
 *
 * @author Melech Mizrachi
 */
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
