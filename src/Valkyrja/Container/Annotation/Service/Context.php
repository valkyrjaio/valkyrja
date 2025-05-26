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
 * Class Context.
 *
 * @author Melech Mizrachi
 */
class Context extends Service
{
    /**
     * The service.
     *
     * @var class-string<\Valkyrja\Container\Contract\Service>|null
     */
    public string|null $service;

    /**
     * Get the service.
     *
     * @return class-string<\Valkyrja\Container\Contract\Service>|null
     */
    public function getService(): string|null
    {
        return $this->service ?? null;
    }

    /**
     * Set the service.
     *
     * @param class-string<\Valkyrja\Container\Contract\Service>|null $service The service
     *
     * @return static
     */
    public function setService(string|null $service = null): static
    {
        $this->service = $service;

        return $this;
    }
}
