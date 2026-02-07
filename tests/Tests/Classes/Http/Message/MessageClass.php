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

namespace Valkyrja\Tests\Classes\Http\Message;

use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Header\Contract\HeaderContract;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Trait\Message;

/**
 * Class MessageClass.
 */
class MessageClass
{
    use Message;

    /**
     * @param HeaderContract[] $headers The headers
     */
    public function __construct(
        protected StreamContract $stream = new Stream(),
        protected ProtocolVersion $protocol = ProtocolVersion::V1_1,
        array $headers = [],
        string|null $testHeader = null,
        string|null $testHeaderOverride = null,
    ) {
        $this->setHeaders(...$headers);

        if ($testHeader !== null) {
            $this->headers = $this->injectHeader(new Header('Test-Header', $testHeader), $this->headers);
        }

        if ($testHeaderOverride !== null) {
            $this->headers = $this->injectHeader(new Header('Test-Header-Override', $testHeaderOverride), $this->headers, true);
        }
    }
}
