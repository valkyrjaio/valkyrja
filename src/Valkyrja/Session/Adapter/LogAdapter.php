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

/**
 * Class LogAdapter.
 *
 * @author Melech Mizrachi
 *
 * @psalm-type ConfigAsArray array{id?: string, name?: string, ...}
 *
 * @phpstan-type ConfigAsArray array{id?: string, name?: string, ...}
 */
class LogAdapter extends PHPAdapter
{
    /**
     * LogAdapter constructor.
     *
     * @param Logger        $logger      The logger
     * @param ConfigAsArray $config      The config
     * @param string|null   $sessionId   [optional] The session id
     * @param string|null   $sessionName [optional] The session name
     */
    public function __construct(
        protected Logger $logger,
        array $config,
        ?string $sessionId = null,
        ?string $sessionName = null
    ) {
        parent::__construct($config, $sessionId, $sessionName);
    }
}
