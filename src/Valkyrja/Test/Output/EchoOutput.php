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

use Valkyrja\Test\Result\Contract\Results;

/**
 * Class Output.
 *
 * @author Melech Mizrachi
 */
class EchoOutput extends Output
{
    /**
     * @inheritDoc
     */
    public function title(): void
    {
        echo $this->formatter->title();
    }

    /**
     * @inheritDoc
     */
    public function meta(): void
    {
        echo $this->formatter->meta();
    }

    /**
     * @inheritDoc
     */
    public function tests(Results $results): void
    {
        echo $this->formatter->tests($results);
    }

    /**
     * @inheritDoc
     */
    public function completed(Results $results): void
    {
        echo $this->formatter->completed($results);
    }

    /**
     * @inheritDoc
     */
    public function results(Results $results): void
    {
        echo $this->formatter->results($results);
    }

    /**
     * @inheritDoc
     */
    public function issues(Results $results): void
    {
        echo $this->formatter->issues($results);
    }

    /**
     * @inheritDoc
     */
    public function sectionSpacing(): void
    {
        echo $this->formatter->sectionSpacing();
    }
}
