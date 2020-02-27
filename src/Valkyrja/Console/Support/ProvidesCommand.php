<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Support;

use Valkyrja\Application\Application;
use Valkyrja\Console\Models\Command;
use Valkyrja\Support\Providers\Provides;

/**
 * Trait ProvidesCommand.
 *
 * @author Melech Mizrachi
 */
trait ProvidesCommand
{
    use Provides;

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
     * Get the provided command.
     *
     * @return array
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
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->console()->addCommand(
            (new Command())
                ->setPath(static::getPath())
                ->setName(static::getCommand())
                ->setDescription(static::getShortDescription())
                ->setClass(static::class)
                ->setClass('run')
        );
    }

    /**
     * Get the command names.
     *
     * @return array
     */
    public static function commands(): array
    {
        return [
            static::getCommand(),
        ];
    }
}
