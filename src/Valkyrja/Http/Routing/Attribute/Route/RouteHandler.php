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

namespace Valkyrja\Http\Routing\Attribute\Route;

use Attribute;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;

#[Attribute(Attribute::TARGET_METHOD)]
class RouteHandler
{
    /** @var callable(ContainerContract, array<string, mixed>):ResponseContract */
    public $handler;

    /**
     * @param callable(ContainerContract, array<string, mixed>):ResponseContract $handler
     */
    public function __construct(
        callable $handler,
    ) {
        $this->handler = $handler;
    }
}
