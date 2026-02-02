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

use Override;
use Valkyrja\Cli\Interaction\Format\Contract\FormatContract;
use Valkyrja\Cli\Interaction\Formatter\Contract\FormatterContract;

use function sprintf;

class Formatter implements FormatterContract
{
    /**
     * @var FormatContract[]
     */
    protected array $formats = [];

    public function __construct(
        FormatContract ...$formats
    ) {
        $this->formats = $formats;
    }

    /**
     * @param array{
     *     formats: FormatContract[],
     * } $array The array
     */
    public static function __set_state(array $array): static
    {
        $formats = $array['formats'];

        return new static(...$formats);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getFormats(): array
    {
        return $this->formats;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withFormats(FormatContract ...$formats): static
    {
        $new = clone $this;

        $new->formats = $formats;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function formatText(string $text): string
    {
        if ($this->formats === []) {
            return $text;
        }

        $set   = [];
        $unset = [];

        foreach ($this->formats as $format) {
            $set[]   = $format->getSetCode();
            $unset[] = $format->getUnsetCode();
        }

        return sprintf("\033[%sm%s\033[%sm", implode(';', $set), $text, implode(';', $unset));
    }
}
