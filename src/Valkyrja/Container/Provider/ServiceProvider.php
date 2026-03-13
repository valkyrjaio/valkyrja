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

namespace Valkyrja\Container\Provider;

use Override;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Generator\Contract\DataProviderFileGeneratorContract;
use Valkyrja\Container\Generator\DataProviderFileGenerator;
use Valkyrja\Container\Manager\Contract\ContainerContract;

final class ServiceProvider extends Provider
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function publishers(): array
    {
        return [
            DataProviderFileGeneratorContract::class => [self::class, 'publishDataFileGenerator'],
            Data::class                              => [self::class, 'publishData'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            DataProviderFileGeneratorContract::class,
            Data::class,
        ];
    }

    /**
     * Publish the data file generator service.
     */
    public static function publishDataFileGenerator(ContainerContract $container): void
    {
        $env = $container->getSingleton(Env::class);

        /** @var non-empty-string $dataPath */
        $dataPath = $env::APP_DATA_PATH;
        /** @var non-empty-string $namespace */
        $namespace = $env::APP_DATA_NAMESPACE;
        /** @var non-empty-string $className */
        $className = $env::CONTAINER_DATA_PROVIDER_CLASS_NAME
            ?? 'ContainerDataProvider';

        $directory = Directory::srcPath($dataPath);

        $data = $container->getData();

        $container->setSingleton(
            DataProviderFileGeneratorContract::class,
            new DataProviderFileGenerator(
                directory: $directory,
                data: $data,
                namespace: $namespace,
                className: $className,
            )
        );
    }

    /**
     * Publish the data service.
     */
    public static function publishData(ContainerContract $container): void
    {
        $app = $container->getSingleton(ApplicationContract::class);

        foreach ($app->getContainerProviders() as $provider) {
            $container->register($provider);
        }

        $dataGenerator = $container->getSingleton(DataProviderFileGeneratorContract::class);
        $dataGenerator->generateFile();

        $container->setSingleton(Data::class, $container->getData());
    }
}
