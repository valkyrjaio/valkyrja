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

use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;

/**
 * Class HelpOptionParameter.
 *
 * @author Melech Mizrachi
 */
class NoInteractionOptionParameter extends OptionParameter
{
    public const string NAME       = 'no-interaction';
    public const string SHORT_NAME = 'N';

    public function __construct()
    {
        parent::__construct(
            name: self::NAME,
            description: 'No interactive questions are asked.',
            shortNames: [self::SHORT_NAME],
            valueMode: OptionValueMode::NONE
        );
    }
}
