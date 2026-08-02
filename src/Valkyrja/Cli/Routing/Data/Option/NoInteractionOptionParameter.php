<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Routing\Data\Option;

use Valkyrja\Cli\Routing\Constant\OptionName;
use Valkyrja\Cli\Routing\Constant\OptionShortName;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;

class NoInteractionOptionParameter extends OptionParameter
{
    public function __construct()
    {
        parent::__construct(
            name: OptionName::NO_INTERACTION,
            description: 'No interactive questions are asked.',
            shortNames: [OptionShortName::NO_INTERACTION],
            valueMode: OptionValueMode::NONE
        );
    }
}
