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

namespace Valkyrja\Path\Generator\Contract;

use Valkyrja\Path\Parser\Contract\Parser;

/**
 * Interface Generator.
 *
 * @author Melech Mizrachi
 *
 * @psalm-import-type ParsedPathParams from Parser
 *
 * @phpstan-import-type ParsedPathParams from Parser
 *
 * @psalm-type DatumParam string|array<array-key, string>|int|bool
 *
 * @phpstan-type DatumParam string|array<array-key, string>|int|bool
 *
 * @psalm-type DataParam array<string, DatumParam>
 *
 * @phpstan-type DataParam array<string, DatumParam>
 */
interface Generator
{
    /**
     * Parse segments, data, and params into a path.
     *
     * @param string[]              $segments The segments
     * @param DataParam|null        $data     [optional] The data
     * @param ParsedPathParams|null $params   [optional] The params
     *
     * @return string
     */
    public function parse(array $segments, ?array $data = null, ?array $params = null): string;
}
