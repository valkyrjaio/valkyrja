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

namespace Valkyrja\Console\Config;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 */
class Cache
{
    /**
     * The base64 encoded serialized commands.
     *
     * @var string
     */
    public string $commands;

    /**
     * The command paths.
     *
     * @var array<non-empty-string, string>
     */
    public array $paths;

    /**
     * The named commands.
     *
     * @var array<string, string>
     */
    public array $namedCommands;

    /**
     * The provided commands.
     *
     * @var array<string, string>
     */
    public array $provided;
}
