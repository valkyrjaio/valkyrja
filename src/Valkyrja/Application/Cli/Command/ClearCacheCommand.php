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

namespace Valkyrja\Application\Cli\Command;

use Valkyrja\Application\Env;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactory;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;
use Valkyrja\Cli\Interaction\Output\Contract\Output;
use Valkyrja\Cli\Routing\Attribute\Command as CommandAttribute;

/**
 * Class ClearCacheCommand.
 *
 * @author Melech Mizrachi
 */
class ClearCacheCommand
{
    #[CommandAttribute(
        name: 'config:clear-cache',
        description: 'Clear config cache',
        helpText: new Message('A command to clear the config cache.'),
    )]
    public function run(Env $env, OutputFactory $outputFactory): Output
    {
        /** @var non-empty-string $cacheFilePath */
        $cacheFilePath = $env::APP_CACHE_FILE_PATH;

        // If the cache file already exists, delete it
        if (is_file($cacheFilePath)) {
            @unlink($cacheFilePath);
        }

        return $outputFactory
            ->createOutput()
            ->withMessages(
                new Banner(new SuccessMessage('Application config cache cleared successfully.')),
                new NewLine(),
            );
    }
}
