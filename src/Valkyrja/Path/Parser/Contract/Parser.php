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
 *
 * @psalm-type ParsedPathParams array<string, array{regex: string, replace: non-falsy-string}>
 *
 * @phpstan-type ParsedPathParams array<string, array{regex: string, replace: non-falsy-string}>
 *
 * @psalm-type ParsedPath array{params: ParsedPathParams, regex: non-falsy-string, segments: string[]}
 *
 * @phpstan-type ParsedPath array{params: ParsedPathParams, regex: non-falsy-string, segments: string[]}
 */
interface Parser
{
    /**
     * Parse a path and get its parts.
     *
     * @param string $path The path
     *
     * @return ParsedPath
     */
    public function parse(string $path): array;
}
