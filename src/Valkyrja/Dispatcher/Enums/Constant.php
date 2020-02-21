<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Dispatcher\Enums;

use Valkyrja\Enum\Enum;

/**
 * Enum Constant.
 *
 * @author Melech Mizrachi
 */
final class Constant extends Enum
{
    /**
     * The return value to use when a dispatch was successful
     * but no data was returned from the dispatch.
     * This avoids having to check each and every
     * other type of dispatch down the chain.
     *
     * @var string
     */
    public const DISPATCHED = 'dispatcher.dispatched';
}
