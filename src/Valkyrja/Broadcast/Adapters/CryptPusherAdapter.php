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

namespace Valkyrja\Broadcast\Adapters;

use Pusher\Pusher;
use Valkyrja\Broadcast\Message;
use Valkyrja\Crypt\Adapter as CryptAdapter;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Crypt\Exceptions\CryptException;

/**
 * Class CryptPusherAdapter.
 *
 * @author Melech Mizrachi
 */
class CryptPusherAdapter extends PusherAdapter
{
    /**
     * The crypt adapter.
     *
     * @var CryptAdapter
     */
    protected CryptAdapter $crypt;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * CryptPusherAdapter constructor.
     *
     * @param Pusher $pusher The pusher service
     * @param Crypt  $crypt  The crypt manager
     * @param array  $config The config
     */
    public function __construct(Pusher $pusher, Crypt $crypt, array $config)
    {
        parent::__construct($pusher);

        $this->config = $config;
        $this->crypt  = $crypt->useCrypt($config['adapter']);
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
