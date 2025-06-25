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

namespace Valkyrja\Cli\Interaction\Formatter;

use Valkyrja\Cli\Interaction\Enum\BackgroundColor;
use Valkyrja\Cli\Interaction\Enum\TextColor;

/**
 * Class ErrorFormatter.
 *
 * @author Melech Mizrachi
 */
class WarningFormatter extends Formatter
{
    public function __construct()
    {
        parent::__construct(
            textColor: TextColor::BLACK,
            backgroundColor: BackgroundColor::YELLOW
        );
    }
}
