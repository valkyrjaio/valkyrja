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

namespace Valkyrja\Session\Manager;

use Override;
use Valkyrja\Crypt\Manager\Contract\CryptContract;
use Valkyrja\Crypt\Throwable\Exception\CryptException;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Session\Data\CookieParams;
use Valkyrja\Session\Throwable\Exception\SessionStartFailure;

use function headers_sent;
use function session_start;

class CookieSession extends PhpSession
{
    /**
     * CookieSession constructor.
     */
    public function __construct(
        protected CryptContract $crypt,
        protected ServerRequestContract $request,
        CookieParams $cookieParams,
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        parent::__construct(
            cookieParams: $cookieParams,
            sessionId: $sessionId,
            sessionName: $sessionName
        );
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException
     */
    #[Override]
    public function start(): void
    {
        // If the session is already active
        if ($this->isActive() || headers_sent()) {
            // No need to reactivate
            return;
        }

        // If the session failed to start
        if (! session_start()) {
            // Throw a new exception
            throw new SessionStartFailure('The session failed to start!');
        }

        $dataString = $this->request->getCookieParam($this->getId());

        // Set the data
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->data = $dataString !== null && $dataString !== ''
            ? $this->crypt->decryptArray($dataString)
            : [];
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException
     */
    #[Override]
    public function set(string $id, $value): void
    {
        $this->data[$id] = $value;

        $this->updateCookieSession();
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException
     */
    #[Override]
    public function remove(string $id): bool
    {
        if (! $this->has($id)) {
            return false;
        }

        unset($this->data[$id]);

        $this->updateCookieSession();

        return true;
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException
     */
    #[Override]
    public function clear(): void
    {
        parent::clear();

        $this->updateCookieSession();
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException
     */
    #[Override]
    public function destroy(): void
    {
        parent::destroy();

        $this->updateCookieSession();
    }

    /**
     * Update the cache session.
     *
     * @throws CryptException
     *
     * @return void
     */
    protected function updateCookieSession(): void
    {
        setcookie(
            $this->getId(),
            $this->crypt->encryptArray($this->data),
            0,
            '/',
            '',
            false,
            true
        );
    }
}
