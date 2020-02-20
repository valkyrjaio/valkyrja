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
     * Get the provided command.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            static::PATH,
        ];
    }

    /**
     * Get the command names.
     *
     * @return array
     */
    public static function commands(): array
    {
        return [
            static::COMMAND,
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
                ->setPath(static::PATH)
                ->setName(static::COMMAND)
                ->setDescription(static::SHORT_DESCRIPTION)
                ->setClass(static::class)
        );
    }
}
