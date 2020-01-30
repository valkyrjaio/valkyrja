<?php

declare(strict_types=1);

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
 * Interface PathParser.
 *
 * @author Melech Mizrachi
 */
interface PathParser
{
    /**
     * Parse a path and get its parts.
     *
     * @param string $path The path
     *
     * @return array
     */
    public function parse(string $path): array;
}
