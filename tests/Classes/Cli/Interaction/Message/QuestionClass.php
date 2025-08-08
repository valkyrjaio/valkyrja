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

namespace Valkyrja\Tests\Classes\Cli\Interaction\Message;

use Valkyrja\Cli\Interaction\Message\Question;

/**
 * Testable Question class.
 *
 * @author Melech Mizrachi
 */
class QuestionClass extends Question
{
    protected function fopen(string $filename, string $mode)
    {
        return parent::fopen(filename: __DIR__ . '/../../../../storage/.gitignore', mode: 'rb');
    }
}
