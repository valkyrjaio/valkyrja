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

namespace Valkyrja\Test\Output;

/**
 * Interface Formatter.
 *
 * @author Melech Mizrachi
 */
interface Formatter
{
    /**
     * Get the formatted title.
     */
    public function title(): string;

    /**
     * Get the formatted meta information.
     */
    public function meta(): string;

    /**
     * Get the formatted tests.
     */
    public function tests(Results $results): string;

    /**
     * Get the formatted completed count.
     */
    public function completed(Results $results): string;

    /**
     * Get the formatted results.
     */
    public function results(Results $results): string;

    /**
     * Get the formatted issues.
     */
    public function issues(Results $results): string;

    /**
     * Get the formatted section spacing.
     */
    public function sectionSpacing(): string;
}
