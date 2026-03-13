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

namespace Valkyrja\Cli\Routing\Generator;

use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\Data;
use Valkyrja\Cli\Routing\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Cli\Routing\Provider\ServiceProvider;
use Valkyrja\Container\Generator\Abstract\ProviderFileGenerator;

use function is_callable;

class DataFileGenerator extends ProviderFileGenerator implements DataFileGeneratorContract
{
    /**
     * @param non-empty-string $directory The directory
     * @param non-empty-string $namespace The class namespace
     * @param non-empty-string $className The class name
     */
    public function __construct(
        protected string $directory,
        protected Data $data,
        protected string $namespace,
        protected string $className,
    ) {
        parent::__construct(
            directory: $directory,
            namespace: $namespace,
            className: $className,
            serviceClassName: 'Data',
            serviceFullNamespace: Data::class,
            publishMethod: 'publishData',
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function generateClassContents(): string
    {
        $dataNamespace = Data::class;

        $routes  = $this->getRoutesAsContent();

        // phpcs:disable
        return <<<PHP
            new \\$dataNamespace(
                routes: $routes,
            )
            PHP;
        // phpcs:enable
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function getPublishContents(): string
    {
        $dataContents = $this->generateClassContents();
        $bypassLogic  = $this->getDataBypassLogic();

        return <<<PHP
            $bypassLogic

            \$data = $dataContents;

            \$container->setSingleton(Data::class, \$data);
            PHP;
    }

    /**
     * Bypass logic for the data.
     */
    protected function getDataBypassLogic(): string
    {
        $applicationContract = ApplicationContract::class;
        $serviceProvider     = ServiceProvider::class;

        // phpcs:disable
        return <<<PHP
            \$app = \$container->getSingleton(\\$applicationContract::class);

            if (\$app->getDebugMode()) {
                \\$serviceProvider::publishData(\$container);

                return;
            }
            PHP;
        // phpcs:enable
    }

    /**
     * Get all routes as a string.
     *
     * @return non-empty-string
     */
    protected function getRoutesAsContent(): string
    {
        $routes = $this->data->routes;

        $routesContent = '';

        foreach ($routes as $key => $route) {
            if (is_callable($route)) {
                $route = $route();
            }

            $routeContent = $this->getRouteAsContent($route);

            $routesContent .= <<<PHP
                '$key' => $routeContent,

                PHP;
        }

        return <<<PHP
            [
                $routesContent
            ]
            PHP;
    }

    /**
     * Get the route as a string.
     *
     * @return non-empty-string
     */
    protected function getRouteAsContent(RouteContract $route): string
    {
        $contract = RouteContract::class;
        $content  = $this->generateObjectsContents($route);

        // phpcs:disable
        return <<<PHP
            static fn (): \\$contract => $content
            PHP;
        // phpcs:enable
    }
}
