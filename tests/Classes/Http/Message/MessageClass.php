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
use Valkyrja\Http\Message\Message;
use Valkyrja\Http\Message\Stream\Contract\Stream;
use Valkyrja\Http\Message\Stream\Stream as HttpStream;

/**
 * Class MessageClass.
 *
 * @author Melech Mizrachi
 */
class MessageClass
{
    use Message;

    /**
     * @param Stream                  $stream
     * @param ProtocolVersion         $protocol
     * @param array<string, string[]> $headers
     */
    public function __construct(
        protected Stream $stream = new HttpStream(),
        protected ProtocolVersion $protocol = ProtocolVersion::V1_1,
        array $headers = []
    ) {
        $this->headers = $headers;
    }
}
