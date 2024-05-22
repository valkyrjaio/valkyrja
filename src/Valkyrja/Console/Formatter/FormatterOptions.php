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

namespace Valkyrja\Console\Formatter;

use Valkyrja\Console\Enum\FormatOption;

/**
 * Trait FormatterFormats.
 *
 * @author Melech Mizrachi
 */
trait FormatterOptions
{
    /**
     * @inheritDoc
     */
    public function bold(): void
    {
        $this->setOptionNum(FormatOption::BOLD);
    }

    /**
     * @inheritDoc
     */
    public function underscore(): void
    {
        $this->setOptionNum(FormatOption::UNDERSCORE);
    }

    /**
     * @inheritDoc
     */
    public function blink(): void
    {
        $this->setOptionNum(FormatOption::BLINK);
    }

    /**
     * @inheritDoc
     */
    public function reverse(): void
    {
        $this->setOptionNum(FormatOption::INVERSE);
    }

    /**
     * @inheritDoc
     */
    public function conceal(): void
    {
        $this->setOptionNum(FormatOption::CONCEAL);
    }

    /**
     * Set an option by its number value.
     *
     * @param FormatOption $option The option
     *
     * @return void
     */
    abstract protected function setOptionNum(FormatOption $option): void;
}
