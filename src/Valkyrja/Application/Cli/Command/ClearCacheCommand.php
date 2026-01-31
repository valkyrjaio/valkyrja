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

use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Message\SuccessMessage;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Support\Directory\Directory;

class ClearCacheCommand
{
    #[Route(
        name: 'config:cache:clear',
        description: 'Clear config cache',
        helpText: new Message('A command to clear the config cache.'),
    )]
    public function run(Env $env, OutputFactoryContract $outputFactory): OutputContract
    {
        /** @var non-empty-string $cacheFilepath */
        $cacheFilepath = $env::APP_CACHE_FILE_PATH;
        $cacheFilename = Directory::basePath($cacheFilepath);

        // If the cache file already exists, delete it
        if (is_file($cacheFilename)) {
            @unlink($cacheFilename);
        }

        return $outputFactory
            ->createOutput()
            ->withMessages(
                new Banner(new SuccessMessage('Application config cache cleared successfully.')),
                new NewLine(),
            );
    }
}
