<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Message;

use Valkyrja\Http\Message\Enum\ProtocolVersion;
use Valkyrja\Http\Message\Header\Collection\Contract\HeaderCollectionContract;
use Valkyrja\Http\Message\Header\Collection\HeaderCollection;
use Valkyrja\Http\Message\Header\Header;
use Valkyrja\Http\Message\Stream\Contract\StreamContract;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Message\Trait\Message;

/**
 * Class MessageFixture.
 */
final class MessageFixture
{
    use Message;

    public function __construct(
        protected StreamContract $stream = new Stream(),
        protected ProtocolVersion $protocol = ProtocolVersion::V1_1,
        protected HeaderCollectionContract $headers = new HeaderCollection(),
        string|null $testHeader = null,
        string|null $testHeaderOverride = null,
    ) {
        if ($testHeader !== null) {
            $this->headers = $this->injectHeader(new Header('Test-Header', $testHeader), $this->headers);
        }

        if ($testHeaderOverride !== null) {
            $this->headers = $this->injectHeader(new Header('Test-Header-Override', $testHeaderOverride), $this->headers, true);
        }
    }
}
