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

namespace Valkyrja\Http\Routing\Attribute\Parameter;

use Attribute;
use Valkyrja\Http\Routing\Constant\Regex as RegexConstant;
use Valkyrja\Http\Routing\Exception\InvalidParameterRegexException;

use function preg_match;

/**
 * Attribute Regex.
 *
 * @author Melech Mizrachi
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class Regex
{
    /**
     * @param non-empty-string $value
     */
    public function __construct(
        public string $value
    ) {
        if (@preg_match(RegexConstant::START . $value . RegexConstant::END, '') === false) {
            throw new InvalidParameterRegexException(
                message: "Invalid parameter regex of `$value` provided"
            );
        }
    }
}
