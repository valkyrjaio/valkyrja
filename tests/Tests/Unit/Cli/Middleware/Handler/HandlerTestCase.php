<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Cli\Middleware\Handler;

use Valkyrja\Cli\Interaction\Input\Input;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Tests\Fixtures\Cli\Routing\Handler\RouteHandlerFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * The Handler test case.
 */
abstract class HandlerTestCase extends TestCase
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
            handler: RouteHandlerFixture::handle(...),
        );
    }
}
