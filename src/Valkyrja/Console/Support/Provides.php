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

namespace Valkyrja\Console\Support;

use Valkyrja\Console\Contract\Console;
use Valkyrja\Console\Model\Command;

/**
 * Trait Provides.
 *
 * @author Melech Mizrachi
 */
trait Provides
{
    /**
     * Whether this provider is deferred.
     *
     * @return bool
     */
    public static function deferred(): bool
    {
        return true;
    }

    /**
     * The items provided by this provider.
     *
     * <code>
     *      [
     *          Provided::class => 'publish',
     *          Provided::class => 'publishProvidedClass',
     *      ]
     *
     * ...
     *      public static function publishProvidedClass(Console $console): void
     * </code>
     *
     * @return string[]
     */
    public static function publishers(): array
    {
        return [];
    }

    /**
     * Get the provided command.
     *
     * @return string[]
     */
    public static function provides(): array
    {
        return [
            static::getPath(),
        ];
    }

    /**
     * Publish the command.
     *
     * @param Console $console The application
     *
     * @return void
     */
    public static function publish(Console $console): void
    {
        $command = new Command();

        $command
            ->setDescription(static::getShortDescription())
            ->setPath(static::getPath())
            ->setName(static::getCommand())
            ->setClass(static::class)
            ->setMethod('run')
            ->setDependencies(static::getCommandDependencies());

        $console->addCommand($command);
    }

    /**
     * Get the command names.
     *
     * @return string[]
     */
    public static function commands(): array
    {
        return [
            static::getCommand(),
        ];
    }

    /**
     * Get the command.
     *
     * @return string
     */
    abstract public static function getCommand(): string;

    /**
     * Get the command path.
     *
     * @return string
     */
    abstract public static function getPath(): string;

    /**
     * Get the short description.
     *
     * @return string
     */
    abstract public static function getShortDescription(): string;

    /**
     * The run handler.
     *
     * @return int
     */
    abstract public function run(): int;

    /**
     * Get the command dependencies.
     *
     * @return class-string[]
     */
    protected static function getCommandDependencies(): array
    {
        return [];
    }
}
