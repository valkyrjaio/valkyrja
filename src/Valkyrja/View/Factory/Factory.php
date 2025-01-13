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

namespace Valkyrja\View\Factory;

use Valkyrja\View\Config;
use Valkyrja\View\Engine\Contract\Engine;
use Valkyrja\View\Factory\Contract\Factory as Contract;
use Valkyrja\View\Template\Contract\Template;

/**
 * Class Factory.
 *
 * @author Melech Mizrachi
 */
class Factory implements Contract
{
    public function __construct(
        protected Config|array $config = new Config(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getTemplate(Engine $engine, string $name, array $variables = []): Template
    {
        $template = new \Valkyrja\View\Template\Template($engine);

        $template->setName($name);
        $template->setVariables($variables);

        return $template;
    }

    /**
     * @inheritDoc
     */
    public function getEngine(string $name): Engine
    {
        return new $name($this->config);
    }
}
