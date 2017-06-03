<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Path;

/**
 * Interface PathGenerator.
 *
 * @author Melech Mizrachi
 */
interface PathGenerator
{
    /**
     * Parse segments, data, and params into a path.
     *
     * @param array $segments The segments
     * @param array $data     [optional] The data
     * @param array $params   [optional] The params
     *
     * @return string
     */
    public function parse(array $segments, array $data = null, array $params = null): string;
}
