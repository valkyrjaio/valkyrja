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

namespace Valkyrja\Cli\Interaction\Output\Contract;

/**
 * Interface StreamOutputContract.
 *
 * @author Melech Mizrachi
 */
interface StreamOutputContract extends OutputContract
{
    /**
     * Get the stream.
     *
     * @return resource
     */
    public function getStream();

    /**
     * Create a new StreamOutput with the specified stream resource.
     *
     * @param resource $stream The stream resource
     *
     * @return static
     */
    public function withStream($stream): static;
}
