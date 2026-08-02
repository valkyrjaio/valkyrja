<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Message\Response;

use Override;
use Valkyrja\Http\Message\Response\Response;

/**
 * Records the native I/O seam calls made by Response when sending, so tests
 * can assert on them without shadowing global functions in the namespace.
 */
final class ResponseSendRecorderFixture extends Response
{
    /** @var array<int, string|bool> */
    public array $sentHeaders = [];

    public int $responseCode = 0;

    public bool $obFlushCalled = false;

    public bool $flushCalled = false;

    /**
     * The output-buffering level to report. Set to 0 to exercise the branch
     * where ob_flush() is skipped.
     */
    public int $obLevel = 1;

    #[Override]
    protected function header(string $header, bool $replace = true, int $responseCode = 0): void
    {
        $this->sentHeaders[] = $header;
        $this->sentHeaders[] = $replace;

        if ($responseCode > 0) {
            $this->responseCode = $responseCode;
        }
    }

    #[Override]
    protected function obGetLevel(): int
    {
        return $this->obLevel;
    }

    #[Override]
    protected function obFlush(): void
    {
        $this->obFlushCalled = true;
    }

    #[Override]
    protected function flush(): void
    {
        $this->flushCalled = true;
    }
}
