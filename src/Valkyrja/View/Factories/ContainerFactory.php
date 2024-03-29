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

namespace Valkyrja\View\Factories;

use Valkyrja\Container\Container;
use Valkyrja\View\Engine;
use Valkyrja\View\Factory as Contract;
use Valkyrja\View\Template;

/**
 * Class Factory.
 *
 * @author Melech Mizrachi
 */
class ContainerFactory implements Contract
{
    public function __construct(
        protected Container $container,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getTemplate(Engine $engine, string $name, array $variables = []): Template
    {
        $template = $this->container->get(Template::class, [$engine]);

        $template->setName($name);
        $template->setVariables($variables);

        return $template;
    }

    /**
     * @inheritDoc
     */
    public function getEngine(string $name): Engine
    {
        return $this->container->getSingleton($name);
    }
}
