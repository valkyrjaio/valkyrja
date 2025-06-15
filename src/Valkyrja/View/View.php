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

namespace Valkyrja\View;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\View\Config\Configuration;
use Valkyrja\View\Contract\View as Contract;
use Valkyrja\View\Engine\Contract\Engine;
use Valkyrja\View\Factory\Contract\Factory;
use Valkyrja\View\Template\Contract\Template;

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
     * The templates.
     *
     * @var Template[]
     */
    protected static array $templates = [];

    /**
     * The body content template.
     *
     * @var string
     */
    protected string $template = 'index';

    /**
     * The view variables.
     *
     * @var array<string, mixed>
     */
    protected array $variables = [];

    /**
     * View constructor.
     */
    public function __construct(
        protected Container $container = new \Valkyrja\Container\Container(),
        protected Factory $factory = new \Valkyrja\View\Factory\Factory(),
        protected Config $config = new Config()
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createTemplate(string $name, array $variables = [], string|null $engine = null): Template
    {
        return $this->factory->getTemplate($this->getEngine($engine), $name, $variables);
    }

    /**
     * @inheritDoc
     */
    public function getEngine(string|null $name = null): Engine
    {
        $name ??= $this->config->defaultConfiguration;

        return self::$engines[$name]
            ??= $this->getEngineFromFactory($name);
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $variables = []): string
    {
        return $this->createTemplate($name, $variables)->render();
    }

    /**
     * Get an engine from the factory given a configuration name.
     */
    protected function getEngineFromFactory(string $configurationName): Engine
    {
        $config = $this->config->configurations->$configurationName;

        if (! $config instanceof Configuration) {
            throw new RuntimeException("$configurationName is an invalid configuration");
        }

        $engineName = $config->engine;

        return $this->factory->getEngine($engineName, $config);
    }
}
