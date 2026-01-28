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

namespace Valkyrja\Cli\Routing\Data\Option;

use Valkyrja\Cli\Routing\Constant\OptionName;
use Valkyrja\Cli\Routing\Constant\OptionShortName;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;

class SilentOptionParameter extends OptionParameter
{
    public function __construct()
    {
        parent::__construct(
            name: OptionName::SILENT,
            description: 'All output is suppressed',
            shortNames: [OptionShortName::SILENT],
            valueMode: OptionValueMode::NONE
        );
    }
}
