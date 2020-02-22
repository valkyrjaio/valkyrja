<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Formatters;

use Valkyrja\Console\Enums\FormatOption;

/**
 * Trait FormatterFormats.
 *
 * @author Melech Mizrachi
 */
trait FormatterOptions
{
    /**
     * Set the bold option.
     *
     * @return void
     */
    public function bold(): void
    {
        $this->setOptionNum(FormatOption::BOLD);
    }

    /**
     * Set the underscore option.
     *
     * @return void
     */
    public function underscore(): void
    {
        $this->setOptionNum(FormatOption::UNDERSCORE);
    }

    /**
     * Set the blink option.
     *
     * @return void
     */
    public function blink(): void
    {
        $this->setOptionNum(FormatOption::BLINK);
    }

    /**
     * Set the reverse option.
     *
     * @return void
     */
    public function reverse(): void
    {
        $this->setOptionNum(FormatOption::INVERSE);
    }

    /**
     * Set the conceal option.
     *
     * @return void
     */
    public function conceal(): void
    {
        $this->setOptionNum(FormatOption::CONCEAL);
    }

    /**
     * Set an option by its number value.
     *
     * @param int $option The option
     *
     * @return void
     */
    abstract protected function setOptionNum(int $option): void;
}
