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

namespace Valkyrja\Path\Parser\Contract;

/**
 * Interface Parser.
 *
 * @author Melech Mizrachi
 */
interface Parser
{
    /**
     * Parse a path and get its parts.
     *
     * @param string $path The path
     *
     * @return array{params: array<string, array{regex: string, replace: non-falsy-string}>, regex: non-falsy-string, segments: string[]}
     */
    public function parse(string $path): array;
}
