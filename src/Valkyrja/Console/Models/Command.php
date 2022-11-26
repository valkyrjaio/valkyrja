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

namespace Valkyrja\Console\Models;

use Valkyrja\Console\Command as Contract;
use Valkyrja\Dispatcher\Models\Dispatch;

/**
 * Class Command.
 *
 * @author Melech Mizrachi
 */
class Command extends Dispatch implements Contract
{
    /**
     * The path.
     *
     * @var string|null
     */
    public ?string $path = null;

    /**
     * The regex for dynamic routes.
     *
     * @var string|null
     */
    public ?string $regex = null;

    /**
     * Any params for dynamic routes.
     *
     * @var array|null
     */
    public ?array $params = null;

    /**
     * Any segments for optional parts of path.
     *
     * @var array|null
     */
    public ?array $segments = null;

    /**
     * The description.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * @inheritDoc
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRegex(): ?string
    {
        return $this->regex;
    }

    /**
     * @inheritDoc
     */
    public function setRegex(string $regex = null): self
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array $params = null): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSegments(): ?array
    {
        return $this->segments;
    }

    /**
     * @inheritDoc
     */
    public function setSegments(array $segments = null): self
    {
        $this->segments = $segments;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function setDescription(string $description = null): self
    {
        $this->description = $description;

        return $this;
    }
}
