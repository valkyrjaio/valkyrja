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

namespace Valkyrja\Cli\Interaction\Format;

use Override;
use Valkyrja\Cli\Interaction\Enum\Style;

class StyleFormat extends Format
{
    public function __construct(Style $style)
    {
        parent::__construct(
            (string) $style->value,
            (string) Style::DEFAULTS[$style->value]
        );
    }

    /**
     * @param array{
     *     setCode: non-empty-string,
     *     unsetCode: non-empty-string,
     * } $array The array
     */
    #[Override]
    public static function __set_state(array $array): static
    {
        return new static(Style::BOLD)
            ->withSetCode($array['setCode'])
            ->withUnsetCode($array['unsetCode']);
    }
}
