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

namespace Valkyrja\Container\Annotation\Service;

use Valkyrja\Container\Annotation\Service;

/**
 * Interface Context.
 *
 * @author Melech Mizrachi
 */
interface Context extends Service
{
    /**
     * Get the service.
     *
     * @return string|null
     */
    public function getService(): ?string;

    /**
     * Set the service.
     *
     * @param string|null $service The service
     *
     * @return $this
     */
    public function setService(string $service = null): self;
}
