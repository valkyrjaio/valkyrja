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

use Override;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Cli\Interaction\Message\Question;

/**
 * Testable Question class.
 */
final class QuestionClass extends Question
{
    #[Override]
    protected function fopen(string $filename, string $mode)
    {
        return parent::fopen(filename: Directory::storagePath('.gitignore'), mode: 'rb');
    }
}
