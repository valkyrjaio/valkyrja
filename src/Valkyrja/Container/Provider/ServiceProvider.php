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
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Directory\Directory;
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Container\Generator\DataFileGenerator;
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
            DataFileGeneratorContract::class => [self::class, 'publishDataFileGenerator'],
            Data::class                      => [self::class, 'publishData'],
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function provides(): array
    {
        return [
            DataFileGeneratorContract::class,
            Data::class,
        ];
    }

    /**
     * Publish the data file generator service.
     */
    public static function publishDataFileGenerator(ContainerContract $container): void
    {
        $env    = $container->getSingleton(Env::class);
        $config = $container->getSingleton(Config::class);

        $dataPath  = $config->dataPath;
        $namespace = $config->dataNamespace;
        /** @var non-empty-string $className */
        $className = $env::CONTAINER_DATA_CLASS_NAME
            ?? 'ContainerData';

        $directory = Directory::srcPath($dataPath);

        $data = $container->getData();

        $container->setSingleton(
            DataFileGeneratorContract::class,
            new DataFileGenerator(
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

        $dataGenerator = $container->getSingleton(DataFileGeneratorContract::class);
        $dataGenerator->generateFile();

        $container->setSingleton(Data::class, $container->getData());
    }
}
