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

namespace Valkyrja\Session\Manager\Cookie;

use JsonException;
use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Session\Manager\Abstract\Session;
use Valkyrja\Type\Array\Factory\ArrayFactory;

class CookieSession extends Session
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     */
    public function __construct(
        protected ServerRequestContract $request,
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        parent::__construct(
            sessionId: $sessionId ?? 'VALKYRJA_SESSION',
            sessionName: $sessionName
        );
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function start(): void
    {
        $id = $this->getId();

        // If the session failed to start
        if ($id === '') {
            return;
        }

        $dataString = $this->request->getCookieParams()->getParam($id);

        // If the session failed to start
        if ($dataString === null || $dataString === '') {
            return;
        }

        $this->setDataFromCookieValue($dataString);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function set(string $id, $value): void
    {
        parent::set($id, $value);

        $this->updateCookieSession();
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function remove(string $id): bool
    {
        $removed = parent::remove($id);

        if ($removed) {
            $this->updateCookieSession();
        }

        return $removed;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
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
     * @throws JsonException
     */
    #[Override]
    public function destroy(): void
    {
        parent::destroy();

        $this->updateCookieSession();
    }

    /**
     * @param non-empty-string $value The cookie value
     *
     * @throws JsonException
     */
    protected function setDataFromCookieValue(string $value): void
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->data = ArrayFactory::fromString($value);
    }

    /**
     * @throws JsonException
     */
    protected function getDataAsCookieValue(): string
    {
        return ArrayFactory::toString($this->data);
    }

    /**
     * Update the cache session.
     *
     * @throws JsonException
     */
    protected function updateCookieSession(): void
    {
        setcookie(
            $this->getId(),
            $this->getDataAsCookieValue(),
            0,
            '/',
            '',
            false,
            true
        );
    }
}
