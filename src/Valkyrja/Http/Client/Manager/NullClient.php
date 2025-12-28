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

namespace Valkyrja\Http\Client\Manager;

use Override;
use Valkyrja\Http\Client\Manager\Contract\Client as Contract;
use Valkyrja\Http\Message\Request\Contract\Request;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Message\Response\EmptyResponse;

/**
 * Class NullClient.
 *
 * @author Melech Mizrachi
 */
class NullClient implements Contract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function sendRequest(Request $request): Response
    {
        return new EmptyResponse();
    }
}
