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

namespace Valkyrja\Console\Enums;

/**
 * Enum InputOption.
 *
 * @author Melech Mizrachi
 */
enum OptionMode: string
{
    case NONE = 'NONE';
    case REQUIRED = 'REQUIRED';
    case OPTIONAL = 'OPTIONAL';
    case IS_ARRAY = 'IS_ARRAY';
}
