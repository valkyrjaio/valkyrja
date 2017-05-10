<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Console\Output;

/**
 * Interface Output.
 *
 *
 * @author  Melech Mizrachi
 */
interface StreamOutput extends Output
{
    /**
     * Output constructor.
     *
     * @param \Valkyrja\Contracts\Console\Output\OutputFormatter $formatter The output formatter
     * @param resource                                           $stream    The resource to use as a stream
     */
    public function __construct(OutputFormatter $formatter, $stream = null);
}
