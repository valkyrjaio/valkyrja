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
 * Interface FileOutputContract.
 *
 * @author Melech Mizrachi
 */
interface FileOutputContract extends OutputContract
{
    /**
     * Get the filepath.
     *
     * @return non-empty-string
     */
    public function getFilepath(): string;

    /**
     * Create a new FileOutput with the specified filepath.
     *
     * @param non-empty-string $filepath The filepath
     *
     * @return static
     */
    public function withFilepath(string $filepath): static;
}
