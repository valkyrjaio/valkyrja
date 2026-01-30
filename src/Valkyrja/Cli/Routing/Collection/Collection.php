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
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Constant\AllowedClasses;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Cli\Routing\Throwable\Exception\RuntimeException;

class Collection implements CollectionContract
{
    /** @var array<string, RouteContract> */
    protected array $commands = [];

    /**
     * @param class-string[] $allowedClasses [optional] The allowed classes to unserialize
     */
    public function __construct(
        protected array $allowedClasses = AllowedClasses::COLLECTION,
    ) {
    }

    /**
     * Get a data representation of the collection.
     */
    #[Override]
    public function getData(): Data
    {
        return new Data(
            commands: array_map(static fn (RouteContract $command): string => serialize($command), $this->commands),
        );
    }

    /**
     * Set data from a data object.
     */
    #[Override]
    public function setFromData(Data $data): void
    {
        foreach ($data->commands as $id => $commandSerialized) {
            /** @var mixed $command */
            $command = unserialize($commandSerialized, ['allowed_classes' => $this->allowedClasses]);

            if (! $command instanceof RouteContract) {
                throw new RuntimeException('Invalid command unserialized');
            }

            $this->commands[$id] = $command;
        }
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function add(RouteContract ...$commands): static
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
    public function get(string $name): RouteContract|null
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
