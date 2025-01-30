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

namespace Valkyrja\Session\Adapter;

use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Crypt\Exception\CryptException;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Session\Exception\SessionStartFailure;

use function headers_sent;
use function session_start;

/**
 * Class CookieAdapter.
 *
 * @author Melech Mizrachi
 *
 * @psalm-import-type ConfigAsArray from NullAdapter
 *
 * @phpstan-import-type ConfigAsArray from NullAdapter
 */
class CookieAdapter extends PHPAdapter
{
    /**
     * The crypt.
     *
     * @var Crypt
     */
    protected Crypt $crypt;

    /**
     * The request.
     *
     * @var ServerRequest
     */
    protected ServerRequest $request;

    /**
     * CookieAdapter constructor.
     *
     * @param Crypt         $crypt       The crypt
     * @param ServerRequest $request     The request
     * @param ConfigAsArray $config      The config
     * @param string|null   $sessionId   [optional] The session id
     * @param string|null   $sessionName [optional] The session name
     */
    public function __construct(
        Crypt $crypt,
        ServerRequest $request,
        array $config,
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        parent::__construct($config, $sessionId, $sessionName);

        $this->crypt   = $crypt;
        $this->request = $request;
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException
     */
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
        $this->data = $dataString !== null && $dataString !== ''
            ? $this->crypt->decryptArray($dataString)
            : [];
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException
     */
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
