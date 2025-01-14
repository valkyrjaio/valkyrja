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

/**
 * Interface Generator.
 *
 * @author Melech Mizrachi
 */
interface Generator
{
    /**
     * Parse segments, data, and params into a path.
     *
     * @param string[]                     $segments The segments
     * @param array<array-key, mixed>|null $data     [optional] The data
     * @param array<array-key, mixed>|null $params   [optional] The params
     *
     * @return string
     */
    public function parse(array $segments, array|null $data = null, array|null $params = null): string;
}
