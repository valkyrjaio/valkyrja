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
    protected string|null $path = null;

    /**
     * The regex for dynamic routes.
     *
     * @var string|null
     */
    protected string|null $regex = null;

    /**
     * Any params for dynamic routes.
     *
     * @var array|null
     */
    protected array|null $params = null;

    /**
     * Any segments for optional parts of path.
     *
     * @var array|null
     */
    protected array|null $segments = null;

    /**
     * The description.
     *
     * @var string|null
     */
    protected string|null $description = null;

    /**
     * @inheritDoc
     */
    public function getPath(): string|null
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRegex(): string|null
    {
        return $this->regex;
    }

    /**
     * @inheritDoc
     */
    public function setRegex(string|null $regex = null): static
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getParams(): array|null
    {
        return $this->params;
    }

    /**
     * @inheritDoc
     */
    public function setParams(array|null $params = null): static
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSegments(): array|null
    {
        return $this->segments;
    }

    /**
     * @inheritDoc
     */
    public function setSegments(array|null $segments = null): static
    {
        $this->segments = $segments;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string|null
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function setDescription(string|null $description = null): static
    {
        $this->description = $description;

        return $this;
    }
}
