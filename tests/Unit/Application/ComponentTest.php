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

namespace Valkyrja\Tests\Unit\Application;

use Valkyrja\Application\Cli\Command\CacheCommand;
use Valkyrja\Application\Cli\Command\ClearCacheCommand;
use Valkyrja\Application\Component;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Component service.
 *
 * @author Melech Mizrachi
 */
class ComponentTest extends TestCase
{
    public function testGetCliControllers(): void
    {
        self::assertContains(CacheCommand::class, Component::getCliControllers());
        self::assertContains(ClearCacheCommand::class, Component::getCliControllers());
    }
}
