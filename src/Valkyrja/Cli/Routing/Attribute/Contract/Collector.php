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

namespace Valkyrja\Cli\Routing\Attribute\Contract;

use Valkyrja\Cli\Routing\Data\Contract\Command;

/**
 * Interface Collector.
 *
 * @author Melech Mizrachi
 */
interface Collector
{
    /**
     * Get the commands.
     *
     * @param class-string ...$classes The classes
     *
     * @return Command[]
     */
    public function getCommands(string ...$classes): array;
}
