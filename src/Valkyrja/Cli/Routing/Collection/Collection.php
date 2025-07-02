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

use Valkyrja\Cli\Routing\Collection\Contract\Collection as Contract;
use Valkyrja\Cli\Routing\Data;
use Valkyrja\Cli\Routing\Data\Contract\Command;
use Valkyrja\Cli\Routing\Exception\RuntimeException;

/**
 * Class Collection.
 *
 * @author Melech Mizrachi
 */
class Collection implements Contract
{
    /** @var array<string, Command> */
    protected array $commands = [];

    /**
     * Get a data representation of the collection.
     */
    public function getData(): Data
    {
        return new Data(
            commands: array_map('serialize', $this->commands),
        );
    }

    /**
     * Set data from a data object.
     */
    public function setFromData(Data $data): void
    {
        foreach ($data->commands as $id => $route) {
            $command = unserialize($route, ['allowed_classes' => true]);

            if (! $command instanceof Command) {
                throw new RuntimeException('Invalid command unserialized');
            }

            $this->commands[$id] = $command;
        }
    }

    /**
     * @inheritDoc
     */
    public function add(Command ...$commands): static
    {
        foreach ($commands as $command) {
            $this->commands[$command->getName()] = $command;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $name): Command|null
    {
        return $this->commands[$name]
            ?? null;
    }

    /**
     * @inheritDoc
     */
    public function has(string $name): bool
    {
        return isset($this->commands[$name]);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->commands;
    }
}
