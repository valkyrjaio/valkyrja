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

use JsonException;
use Override;
use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Session\Manager\Abstract\Session;
use Valkyrja\Type\Array\Factory\ArrayFactory;

class LogSession extends Session
{
    /**
     * @param non-empty-string|null $sessionId   The session id
     * @param non-empty-string|null $sessionName The session id
     */
    public function __construct(
        protected LoggerContract $logger,
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        parent::__construct(
            sessionId: $sessionId,
            sessionName: $sessionName
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function start(): void
    {
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

        $this->updateLogSession();
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
            $this->updateLogSession();
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

        $this->updateLogSession();
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

        $this->updateLogSession();
    }

    /**
     * Get the cache session id.
     */
    protected function getCacheSessionId(): string
    {
        return $this->getId() . '_session';
    }

    /**
     * @throws JsonException
     */
    protected function getDataAsLoggableValue(): string
    {
        return ArrayFactory::toString($this->data);
    }

    /**
     * Update the cache session.
     *
     * @throws JsonException
     */
    protected function updateLogSession(): void
    {
        $this->logger->info(
            $this->getCacheSessionId() . "\n" . $this->getDataAsLoggableValue()
        );
    }
}
