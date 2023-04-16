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
 * Interface Output.
 *
 * @author Melech Mizrachi
 */
interface Output
{
    /**
     * Output the title.
     */
    public function title(): void;

    /**
     * Output the meta information.
     */
    public function meta(): void;

    /**
     * Output the tests.
     */
    public function tests(Results $results): void;

    /**
     * Output the completed count.
     */
    public function completed(Results $results): void;

    /**
     * Output the results.
     */
    public function results(Results $results): void;

    /**
     * Output the issues.
     */
    public function issues(Results $results): void;

    /**
     * Output the section spacing.
     */
    public function sectionSpacing(): void;

    /**
     * Output fully.
     */
    public function full(Results $results): void;
}
