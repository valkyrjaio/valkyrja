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

use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\Data;

interface CollectionContract
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
     * @param RouteContract ...$commands The commands
     */
    public function add(RouteContract ...$commands): static;

    /**
     * Get a command.
     *
     * @param string $name The command name
     */
    public function get(string $name): RouteContract|null;

    /**
     * Determine if a command exists.
     *
     * @param string $name The command name
     */
    public function has(string $name): bool;

    /**
     * Get all the commands.
     *
     * @return array<string, RouteContract>
     */
    public function all(): array;
}
