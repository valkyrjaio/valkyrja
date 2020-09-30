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

namespace Valkyrja\Container\Annotation\Models;

use Valkyrja\Container\Annotation\Service\Context as Contract;

/**
 * Class ServiceContext.
 *
 * @author Melech Mizrachi
 */
class Context extends Service implements Contract
{
    /**
     * The service.
     *
     * @var string|null
     */
    public ?string $service = null;

    /**
     * Get the service.
     *
     * @return string|null
     */
    public function getService(): ?string
    {
        return $this->service;
    }

    /**
     * Set the service.
     *
     * @param string|null $service The service
     *
     * @return static
     */
    public function setService(string $service = null): self
    {
        $this->service = $service;

        return $this;
    }
}
