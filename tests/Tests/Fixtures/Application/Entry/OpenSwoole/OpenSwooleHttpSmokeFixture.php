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

namespace Valkyrja\Tests\Fixtures\Application\Entry\OpenSwoole;

use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use Override;
use Valkyrja\Application\Entry\OpenSwoole\OpenSwooleHttp;

/**
 * Smoke-test OpenSwooleHttp subclass.
 *
 * Leaves the request conversion and dispatch pipeline real (bootstrap, request
 * building, and handleSwooleRequest() all run for real against a booted app);
 * only the irreducible OpenSwoole I/O seams are doubled — the raw body is fed in
 * and the emitted status/headers/body are recorded — so a full onRequest() round
 * trip can be asserted without a live OpenSwoole connection.
 */
final class OpenSwooleHttpSmokeFixture extends OpenSwooleHttp
{
    public static string $rawContent = '';

    public static int|null $sentStatus = null;

    public static string|null $sentReasonPhrase = null;

    /** @var list<array{name: string, value: string}> */
    public static array $sentHeaders = [];

    public static string|null $sentBody = null;

    /**
     * Reset all recorded state between tests.
     */
    public static function reset(): void
    {
        self::$rawContent       = '';
        self::$sentStatus       = null;
        self::$sentReasonPhrase = null;
        self::$sentHeaders      = [];
        self::$sentBody         = null;
    }

    #[Override]
    protected static function getContentFromSwooleRequest(Request $request): string
    {
        return self::$rawContent;
    }

    #[Override]
    protected static function sendSwooleStatus(Response $response, int $statusCode, string $reasonPhrase): void
    {
        self::$sentStatus       = $statusCode;
        self::$sentReasonPhrase = $reasonPhrase;
    }

    #[Override]
    protected static function sendSwooleHeader(Response $response, string $name, string $value): void
    {
        self::$sentHeaders[] = [
            'name'  => $name,
            'value' => $value,
        ];
    }

    #[Override]
    protected static function sendSwooleBody(Response $response, string $body): void
    {
        self::$sentBody = $body;
    }
}
