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

namespace Valkyrja\Cli\Routing\Collection\Contract;

use Valkyrja\Cli\Routing\Data\Contract\Command;

/**
 * Interface Collection.
 *
 * @author Melech Mizrachi
 */
interface Collection
{
    /**
     * Add commands.
     *
     * @param Command ...$commands The commands
     *
     * @return static
     */
    public function add(Command ...$commands): static;

    /**
     * Get a command.
     *
     * @param string $name The command name
     *
     * @return Command|null
     */
    public function get(string $name): Command|null;

    /**
     * Determine if a command exists.
     *
     * @param string $name The command name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * Get all the commands.
     *
     * @return array<string, Command>
     */
    public function all(): array;
}
