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

namespace Valkyrja\Container\Generator;

use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Generator\Abstract\ProviderFileGenerator;
use Valkyrja\Container\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Container\Provider\ServiceProvider;

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
        $data          = $this->data;
        $dataNamespace = Data::class;

        $aliases          = var_export($data->aliases, true);
        $deferred         = var_export($data->deferred, true);
        $deferredCallback = var_export($data->deferredCallback, true);
        $services         = var_export($data->services, true);
        $singletons       = var_export($data->singletons, true);
        $providers        = var_export($data->providers, true);

        // phpcs:disable
        return <<<PHP
            new \\$dataNamespace(
                aliases: $aliases,
                deferred: $deferred,
                deferredCallback: $deferredCallback,
                services: $services,
                singletons: $singletons,
                providers: $providers
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
}
