<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Interaction\Message;

use Override;
use Valkyrja\Cli\Interaction\Message\Question;

/**
 * Testable Question class.
 */
final class QuestionFalseFgetsFixture extends Question
{
    #[Override]
    protected function fopen(string $filename, string $mode)
    {
        return parent::fopen(filename: 'php://memory', mode: 'rb');
    }

    #[Override]
    protected function fgets($stream): string|false
    {
        return false;
    }
}
