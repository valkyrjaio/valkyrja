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
use Valkyrja\View\Constant\ConfigValue;
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
     * The fully qualified template path.
     *
     * @var string
     */
    protected string $templatePath;

    /**
     * The view variables.
     *
     * @var array<string, mixed>
     */
    protected array $variables = [];

    /**
     * The engines.
     *
     * @var array<string, class-string<Engine>>
     */
    protected array $enginesConfig;

    /**
     * The default engine.
     *
     * @var string
     */
    protected string $engine;

    /**
     * View constructor.
     *
     * @param Container                   $container The container
     * @param Config|array<string, mixed> $config    The config
     */
    public function __construct(
        protected Container $container = new \Valkyrja\Container\Container(),
        protected Factory $factory = new \Valkyrja\View\Factory\Factory(),
        protected Config|array $config = new Config()
    ) {
        $this->engine        = $config['engine'] ?? ConfigValue::ENGINE;
        $this->enginesConfig = $config['engines'] ?? ConfigValue::ENGINES;
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
        $name ??= $this->engine;

        return self::$engines[$name]
            ??= $this->factory->getEngine($this->enginesConfig[$name]);
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $variables = []): string
    {
        return $this->createTemplate($name, $variables)->render();
    }
}
