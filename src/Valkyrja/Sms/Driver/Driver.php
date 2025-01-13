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

namespace Valkyrja\Sms\Driver;

use Valkyrja\Manager\Driver\Driver as ParentDriver;
use Valkyrja\Sms\Adapter\Contract\Adapter;
use Valkyrja\Sms\Driver\Contract\Driver as Contract;
use Valkyrja\Sms\Message\Contract\Message;

/**
 * Class Driver.
 *
 * @author Melech Mizrachi
 *
 * @property Adapter $adapter
 */
class Driver extends ParentDriver implements Contract
{
    /**
     * Driver constructor.
     *
     * @param Adapter $adapter The adapter
     */
    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
    }

    /**
     * @inheritDoc
     */
    public function send(Message $message): void
    {
        $this->adapter->send($message);
    }
}
