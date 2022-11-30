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

namespace Valkyrja\View\Managers;

use Valkyrja\Container\Container;
use Valkyrja\View\Config\Config;
use Valkyrja\View\Engine;
use Valkyrja\View\Template;
use Valkyrja\View\View as Contract;

/**
 * Class View.
 *
 * @author Melech Mizrachi
 */
class View implements Contract
{
    /**
     * The engines.
     *
     * @var Engine[]
     */
    protected static array $engines = [];

    /**
     * The body content template.
     *
     * @var string
     */
    protected string $template = 'index';

    /**
     * The fully qualified template path.
     *
     * @var string
     */
    protected string $templatePath;

    /**
     * The view variables.
     *
     * @var array
     */
    protected array $variables = [];

    /**
     * The default engine.
     *
     * @var string
     */
    protected string $engine;

    /**
     * View constructor.
     *
     * @param Container    $container The container
     * @param Config|array $config    The config
     */
    public function __construct(
        protected Container $container,
        protected Config|array $config
    ) {
        $this->engine = $config['engine'];
    }

    /**
     * @inheritDoc
     */
    public function createTemplate(string $name = null, array $variables = [], string $engine = null): Template
    {
        $template = \Valkyrja\View\Templates\Template::createTemplate($this->getEngine($engine));

        if (null !== $name) {
            $template->setName($name);
        }

        $template->setVariables($variables);

        return $template;
    }

    /**
     * @inheritDoc
     */
    public function getEngine(string $name = null): Engine
    {
        $name ??= $this->config['engine'];

        return self::$engines[$name]
            ??= $this->container->getSingleton($this->config['engines'][$name]);
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $variables = []): string
    {
        return $this->createTemplate($name, $variables)->render();
    }
}
