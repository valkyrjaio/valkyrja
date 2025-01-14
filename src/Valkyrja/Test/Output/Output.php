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

use Valkyrja\Test\Formatter\Contract\Formatter;
use Valkyrja\Test\Formatter\Formatter as DefaultFormatter;
use Valkyrja\Test\Output\Contract\Output as Contract;
use Valkyrja\Test\Result\Contract\Results;

/**
 * Class Output.
 *
 * @author Melech Mizrachi
 */
abstract class Output implements Contract
{
    public function __construct(
        protected Formatter $formatter = new DefaultFormatter(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function full(Results $results): void
    {
        $this->title();
        $this->sectionSpacing();
        $this->tests($results);
        $this->sectionSpacing();
        $this->completed($results);
        $this->sectionSpacing();
        $this->meta();
        $this->sectionSpacing();
        $this->results($results);
        $this->issues($results);
    }

    /**
     * @inheritDoc
     */
    abstract public function title(): void;

    /**
     * @inheritDoc
     */
    abstract public function meta(): void;

    /**
     * @inheritDoc
     */
    abstract public function tests(Results $results): void;

    /**
     * @inheritDoc
     */
    abstract public function completed(Results $results): void;

    /**
     * @inheritDoc
     */
    abstract public function results(Results $results): void;

    /**
     * @inheritDoc
     */
    abstract public function issues(Results $results): void;

    /**
     * @inheritDoc
     */
    abstract public function sectionSpacing(): void;
}
