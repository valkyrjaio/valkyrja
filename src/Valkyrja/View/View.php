<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\View;

use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Support\Directory;
use Valkyrja\Support\Provides;

/**
 * Class View.
 *
 * @author Melech Mizrachi
 */
class View implements ViewContract
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected $app;

    /**
     * The layout template.
     *
     * @var string
     */
    protected $layout = 'layout';

    /**
     * The body content template.
     *
     * @var
     */
    protected $template = 'index';

    /**
     * The template directory.
     *
     * @var string
     */
    protected $templateDir;

    /**
     * @var string
     */
    protected $fileExtension = '.php';

    /**
     * The view variables.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * View constructor.
     *
     * @param \Valkyrja\Contracts\Application $app       The application
     * @param string                          $template  [optional] The template to set
     * @param array                           $variables [optional] The variables to set
     */
    public function __construct(Application $app, string $template = '', array $variables = [])
    {
        $this->app = $app;
        $this->setVariables($variables);
        $this->setTemplate($template);
        $this->setTemplateDir($this->app->config()['views']['dir']);
    }

    /**
     * Make a new View.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function make(string $template = '', array $variables = []): ViewContract
    {
        return new static($this->app, $template, $variables);
    }

    /**
     * Set the master template.
     *
     * @param string $template The master template to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function setLayout(string $template): ViewContract
    {
        $this->layout = $template;

        return $this;
    }

    /**
     * Set to use no layout.
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function withoutLayout(): ViewContract
    {
        $this->layout = null;

        return $this;
    }

    /**
     * Set the template.
     *
     * @param string $template The template to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function setTemplate(string $template): ViewContract
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Set the variables.
     *
     * @param array $variables [optional] The variables to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function setVariables(array $variables = []): ViewContract
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }

    /**
     * Set a single variable.
     *
     * @param string $key   The variable key to set
     * @param mixed  $value The value to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function variable(string $key, $value): ViewContract
    {
        $this->variables[$key] = $value;

        return $this;
    }

    /**
     * Get the template directory.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function getTemplateDir(string $path = null): string
    {
        return $this->templateDir . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
                : $path);
    }

    /**
     * Set the template directory.
     *
     * @param string $templateDir The path to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function setTemplateDir(string $templateDir): ViewContract
    {
        $this->templateDir = $templateDir;

        return $this;
    }

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    /**
     * Set the file extension.
     *
     * @param string $extension The extension to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function setFileExtension(string $extension): ViewContract
    {
        $this->fileExtension = $extension;

        return $this;
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->getTemplateDir($this->template . $this->getFileExtension());
    }

    /**
     * Get the layout template path.
     *
     * @return string
     */
    public function getLayoutPath(): string
    {
        return $this->getTemplateDir($this->layout . $this->getFileExtension());
    }

    /**
     * Render the templates and view.
     *
     * @param array $variables [optional] The variables to set
     *
     * @return string
     */
    public function render(array $variables = []): string
    {
        extract(array_merge($this->variables, $variables), EXTR_OVERWRITE);

        ob_start();
        include $this->getTemplatePath();
        $view = ob_get_clean();

        if (null === $this->layout) {
            return $view;
        }

        extract(['body' => $view], EXTR_OVERWRITE);

        ob_start();
        include $this->getLayoutPath();

        return ob_get_clean();
    }

    /**
     * Get the view as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CoreComponent::VIEW,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::VIEW,
            new static($app)
        );
    }
}
