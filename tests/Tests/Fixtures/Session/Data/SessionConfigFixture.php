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

namespace Valkyrja\Tests\Fixtures\Session\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Http\Message\Enum\SameSite;
use Valkyrja\Session\Data\Contract\SessionConfigContract;
use Valkyrja\Session\Data\Contract\SessionCookieConfigContract;
use Valkyrja\Session\Data\Contract\SessionJwtConfigContract;
use Valkyrja\Session\Data\Contract\SessionTokenConfigContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\NullSession;

/**
 * An application config that implements every session contract at once.
 *
 * The adapter contracts prefix each property with the adapter name, so one class
 * can carry the settings for several adapters without a name collision.
 */
final class SessionConfigFixture extends Config implements SessionConfigContract, SessionCookieConfigContract, SessionJwtConfigContract, SessionTokenConfigContract
{
    /**
     * @param class-string<SessionContract> $defaultSession
     * @param non-empty-string|null         $sessionId
     * @param non-empty-string|null         $sessionName
     * @param non-empty-string              $cookiePath
     * @param non-empty-string|null         $cookieDomain
     * @param non-empty-string|null         $jwtOptionName
     * @param non-empty-string|null         $jwtHeaderName
     * @param non-empty-string|null         $tokenOptionName
     * @param non-empty-string|null         $tokenHeaderName
     */
    public function __construct(
        public string $defaultSession = NullSession::class,
        public string|null $sessionId = 'test-id',
        public string|null $sessionName = 'test-name',
        public string $cookiePath = '/test',
        public string|null $cookieDomain = 'test.dev',
        public int $cookieLifetime = 3600,
        public bool $cookieSecure = true,
        public bool $cookieHttpOnly = true,
        public SameSite $cookieSameSite = SameSite::STRICT,
        public string|null $jwtOptionName = 'test-jwt-option',
        public string|null $jwtHeaderName = 'test-jwt-header',
        public string|null $tokenOptionName = 'test-token-option',
        public string|null $tokenHeaderName = 'test-token-header',
    ) {
        parent::__construct();
    }
}
