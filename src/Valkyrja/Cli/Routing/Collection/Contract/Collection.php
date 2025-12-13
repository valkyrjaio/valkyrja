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

use Valkyrja\Cli\Routing\Data;
use Valkyrja\Cli\Routing\Data\Contract\Route;

/**
 * Interface Collection.
 *
 * @author Melech Mizrachi
 */
interface Collection
{
    /**
     * Get a data representation of the collection.
     */
    public function getData(): Data;

    /**
     * Set data from a data object.
     */
    public function setFromData(Data $data): void;

    /**
     * Add commands.
     *
     * @param Route ...$commands The commands
     *
     * @return static
     */
    public function add(Route ...$commands): static;

    /**
     * Get a command.
     *
     * @param string $name The command name
     *
     * @return Route|null
     */
    public function get(string $name): Route|null;

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
     * @return array<string, Route>
     */
    public function all(): array;
}
