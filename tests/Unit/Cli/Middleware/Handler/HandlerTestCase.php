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

namespace Valkyrja\Tests\Unit\Cli\Middleware\Handler;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Dispatch\Data\MethodDispatch as DefaultDispatch;
use Valkyrja\Tests\Unit\TestCase;

/**
 * The Handler test case.
 */
class HandlerTestCase extends TestCase
{
    protected Container $container;

    protected Input $input;

    protected Output $output;

    protected Route $command;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->container = new Container();

        $this->input   = new Input();
        $this->output  = new Output();
        $this->command = new Route(
            name: 'test',
            description: 'Test Command',
            helpText: new Message('text'),
            dispatch: new DefaultDispatch(self::class, 'dispatch')
        );
    }
}
