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

use Valkyrja\Log\Driver\Contract\Driver as Logger;
use Valkyrja\Session\Config\LogConfiguration;

/**
 * Class LogAdapter.
 *
 * @author Melech Mizrachi
 */
class LogAdapter extends PHPAdapter
{
    /**
     * LogAdapter constructor.
     */
    public function __construct(
        protected Logger $logger,
        LogConfiguration $config,
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        parent::__construct($config, $sessionId, $sessionName);
    }
}
