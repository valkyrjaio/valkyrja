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
use Valkyrja\Application\Env\Env;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Data\Data;
use Valkyrja\Container\Generator\Contract\DataFileGeneratorContract;
use Valkyrja\Container\Generator\DataFileGenerator;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Support\Directory\Directory;

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
        $env = $container->getSingleton(Env::class);

        /** @var non-empty-string $dataFilePath */
        $dataFilePath         = $env::CONTAINER_DATA_FILE_PATH
            ?? '/container.php';
        $absoluteDataFilePath = Directory::dataPath($dataFilePath);

        $data = $container->getData();

        $container->setSingleton(
            DataFileGeneratorContract::class,
            new DataFileGenerator(
                filePath: $absoluteDataFilePath,
                data: $data,
            )
        );
    }

    public static function publishData(ContainerContract $container): void
    {
        $data = null;
        $env  = $container->getSingleton(Env::class);

        /** @var bool $useData */
        $useData = $env::CONTAINER_USE_DATA
            ?? false;
        /** @var non-empty-string $dataFilePath */
        $dataFilePath = $env::CONTAINER_DATA_FILE_PATH
            ?? '/container.php';
        $absoluteDataFilePath = Directory::dataPath($dataFilePath);

        if ($useData && is_file(filename: $absoluteDataFilePath)) {
            /**
             * @psalm-suppress UnresolvableInclude
             *
             * @var mixed $data The data
             */
            $data = require $absoluteDataFilePath;
        }

        if ($data instanceof Data) {
            $container->setSingleton(Data::class, $data);

            return;
        }

        $app = $container->getSingleton(ApplicationContract::class);

        foreach ($app->getContainerProviders() as $provider) {
            $container->register($provider);
        }

        $dataGenerator = $container->getSingleton(DataFileGeneratorContract::class);
        $dataGenerator->generateFile();

        $container->setSingleton(Data::class, $container->getData());
    }
}
