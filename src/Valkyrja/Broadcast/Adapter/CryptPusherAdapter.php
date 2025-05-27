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

namespace Valkyrja\Broadcast\Adapter;

use Pusher\Pusher;
use Valkyrja\Broadcast\Config\PusherConfiguration;
use Valkyrja\Broadcast\Message\Contract\Message;
use Valkyrja\Crypt\Contract\Crypt;
use Valkyrja\Crypt\Driver\Contract\Driver as CryptDriver;
use Valkyrja\Crypt\Exception\CryptException;

/**
 * Class CryptPusherAdapter.
 *
 * @author Melech Mizrachi
 */
class CryptPusherAdapter extends PusherAdapter
{
    /**
     * The crypt driver.
     *
     * @var CryptDriver
     */
    protected CryptDriver $crypt;

    /**
     * CryptPusherAdapter constructor.
     */
    public function __construct(
        Pusher $pusher,
        Crypt $crypt,
        protected PusherConfiguration $config
    ) {
        parent::__construct($pusher);

        $this->crypt = $crypt->use();
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException On a crypt failure
     */
    public function determineKeyValueMatch(string $key, $value, string $message): bool
    {
        $decryptedMessage = $this->crypt->decrypt($message);

        return parent::determineKeyValueMatch($key, $value, $decryptedMessage);
    }

    /**
     * @inheritDoc
     *
     * @throws CryptException On a crypt failure
     */
    protected function prepareMessage(Message $message): void
    {
        parent::prepareMessage($message);

        $message->setMessage(
            $this->crypt->encrypt($message->getMessage())
        );
    }
}
