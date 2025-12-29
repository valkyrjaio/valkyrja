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

namespace Valkyrja\Cli\Routing\Collection;

use Override;
use Valkyrja\Cli\Routing\Collection\Contract\Collection as Contract;
use Valkyrja\Cli\Routing\Data\Contract\Route;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Cli\Routing\Exception\RuntimeException;

/**
 * Class Collection.
 *
 * @author Melech Mizrachi
 */
class Collection implements Contract
{
    /** @var array<string, Route> */
    protected array $commands = [];

    /**
     * Get a data representation of the collection.
     */
    #[Override]
    public function getData(): Data
    {
        return new Data(
            commands: array_map('serialize', $this->commands),
        );
    }

    /**
     * Set data from a data object.
     */
    #[Override]
    public function setFromData(Data $data): void
    {
        foreach ($data->commands as $id => $commandSerialized) {
            $command = unserialize($commandSerialized, ['allowed_classes' => true]);

            if (! $command instanceof Route) {
                throw new RuntimeException('Invalid command unserialized');
            }

            $this->commands[$id] = $command;
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function add(Route ...$commands): static
    {
        foreach ($commands as $command) {
            $this->commands[$command->getName()] = $command;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function get(string $name): Route|null
    {
        return $this->commands[$name]
            ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function has(string $name): bool
    {
        return isset($this->commands[$name]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function all(): array
    {
        return $this->commands;
    }
}
