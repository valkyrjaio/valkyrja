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
use Valkyrja\Cli\Interaction\Enum\BackgroundColor;
use Valkyrja\Cli\Interaction\Enum\Style;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Formatter\Contract\Formatter as Contract;

use function count;
use function sprintf;

/**
 * Class Formatter.
 *
 * @author Melech Mizrachi
 */
class Formatter implements Contract
{
    public function __construct(
        protected Style|null $style = null,
        protected TextColor|null $textColor = null,
        protected BackgroundColor|null $backgroundColor = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getStyle(): Style|null
    {
        return $this->style;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withStyle(Style|null $style): static
    {
        $new = clone $this;

        $this->style = $style;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getTextColor(): TextColor|null
    {
        return $this->textColor;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withTextColor(TextColor|null $textColor): static
    {
        $new = clone $this;

        $this->textColor = $textColor;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getBackgroundColor(): BackgroundColor|null
    {
        return $this->backgroundColor;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withBackgroundColor(BackgroundColor|null $backgroundColor): static
    {
        $new = clone $this;

        $this->backgroundColor = $backgroundColor;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function formatText(string $text): string
    {
        $set   = [];
        $unset = [];

        // Check if a foreground was specified
        if ($this->textColor !== null) {
            $set[]   = $this->textColor->value;
            $unset[] = TextColor::DEFAULT->value;
        }

        // Check if a background was specified
        if ($this->backgroundColor !== null) {
            $set[]   = $this->backgroundColor->value;
            $unset[] = BackgroundColor::DEFAULT->value;
        }

        // // Check if options were specified
        // if (count($this->style)) {
        //     // Iterate through all the options
        //     foreach ($this->options as $option) {
        //         $set[]   = $option;
        //         $unset[] = FormatOption::DEFAULT[$option];
        //     }
        // }

        // No need to format if there's nothing to set
        if (count($set) === 0) {
            return $text;
        }

        return sprintf("\033[%sm%s\033[%sm", implode(';', $set), $text, implode(';', $unset));
    }
}
