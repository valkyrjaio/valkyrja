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

namespace Valkyrja\Tests\Unit\Cli\Interaction\Writer;

use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Interaction\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Cli\Interaction\Writer\QuestionWriter;
use Valkyrja\Tests\Unit\Abstract\TestCase;

class QuestionWriterTest extends TestCase
{
    public function testInvalidMessage(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $questionWriter = new QuestionWriter();

        $questionWriter->write(new Output(), new Message('text'));
    }
}
