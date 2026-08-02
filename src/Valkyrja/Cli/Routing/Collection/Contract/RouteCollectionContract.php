<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Routing\Collection\Contract;

use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

interface RouteCollectionContract
{
    /**
     * Get a data representation of the collection.
     */
    public function getData(): CliRoutingData;

    /**
     * Set data from a data object.
     */
    public function setFromData(CliRoutingData $data): void;

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
    public function get(string $name): RouteContract;

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
