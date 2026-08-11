<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Functional\Cli\Server\Command;

use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Attribute\Collector\Collector;
use Valkyrja\Cli\Interaction\Input\Factory\InputFactory;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Header;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Routing\Collection\Contract\RouteCollectionContract;
use Valkyrja\Cli\Routing\Collection\RouteCollection;
use Valkyrja\Cli\Routing\Collector\AttributeRouteCollector;
use Valkyrja\Cli\Routing\Dispatcher\Router;
use Valkyrja\Cli\Server\Command\ListBashCommand;
use Valkyrja\Cli\Server\Command\ListCommand;
use Valkyrja\Cli\Server\Command\VersionCommand;
use Valkyrja\Cli\Server\Provider\CliServerServiceProvider;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Reflection\Reflector\Reflector;
use Valkyrja\Tests\Functional\Abstract\TestCase;

use function array_map;
use function implode;

/**
 * Parameter presence for the built-in commands, over a real dispatch.
 *
 * A route keeps every parameter it declares, so a declared parameter is present
 * on the route whether or not the command line spelled it. Each test dispatches
 * an argv array through the real router and asserts the branch the command took.
 */
final class CommandParameterPresenceTest extends TestCase
{
    /** @var non-empty-string */
    private const string APP_NAMESPACE = 'PresenceApp';

    /** @var non-empty-string */
    private const string APP_VERSION = '9.8.7';

    /**
     * A bare `version` shows the banner, because neither flag was spelled.
     */
    public function testVersionWithoutFlagsShowsTheBanner(): void
    {
        $output = $this->dispatch(['cli', 'version']);

        self::assertInstanceOf(Header::class, $output->getMessages()[0]);
    }

    /**
     * `--short` shows the version number by itself.
     */
    public function testVersionShortShowsTheVersionOnly(): void
    {
        $output = $this->dispatch(['cli', 'version', '--short']);

        self::assertSame(expected: self::APP_VERSION, actual: $this->text($output));
    }

    /**
     * `--plain` shows the version info without the banner.
     */
    public function testVersionPlainShowsTheInfoWithoutTheBanner(): void
    {
        $text = $this->text($this->dispatch(['cli', 'version', '--plain']));

        self::assertStringContainsString(
            needle: self::APP_NAMESPACE . ' v' . self::APP_VERSION,
            haystack: $text
        );
        self::assertStringContainsString(needle: 'Built on Valkyrja v', haystack: $text);
        self::assertStringNotContainsString(needle: '╭──', haystack: $text);
    }

    /**
     * A bare `list` filters nothing, so every command stays in the output.
     */
    public function testListWithoutTheNamespaceOptionListsEveryCommand(): void
    {
        $text = $this->text($this->dispatch(['cli', 'list']));

        self::assertStringContainsString(needle: 'Commands:', haystack: $text);
        self::assertStringContainsString(needle: 'version', haystack: $text);
        self::assertStringContainsString(needle: 'list:bash', haystack: $text);
    }

    /**
     * `--namespace` filters the output down to the commands under that namespace.
     */
    public function testListWithTheNamespaceOptionFilters(): void
    {
        $text = $this->text($this->dispatch(['cli', 'list', '--namespace=list']));

        self::assertStringContainsString(needle: 'Commands [list]:', haystack: $text);
        self::assertStringContainsString(needle: 'list:bash', haystack: $text);
        self::assertStringNotContainsString(needle: 'version', haystack: $text);
    }

    /**
     * `list:bash` with only the application-name argument filters nothing.
     */
    public function testListBashWithoutTheNamespaceArgumentListsEveryCommand(): void
    {
        $text = $this->text($this->dispatch(['cli', 'list:bash', 'cli']));

        self::assertStringContainsString(needle: 'version', haystack: $text);
        self::assertStringContainsString(needle: 'list:bash', haystack: $text);
    }

    /**
     * A spelled namespace argument filters, and the colon trims the prefix.
     */
    public function testListBashWithTheNamespaceArgumentFilters(): void
    {
        $text = $this->text($this->dispatch(['cli', 'list:bash', 'cli', 'list:']));

        self::assertSame(expected: 'bash', actual: $text);
    }

    /**
     * Dispatch an argv array through the real router and return the output.
     *
     * @param non-empty-string[] $argv The argv
     */
    private function dispatch(array $argv): OutputContract
    {
        $reflector      = new Reflector();
        $routeCollector = new AttributeRouteCollector(
            attributes: new Collector($reflector),
            reflection: $reflector
        );

        $collection = new RouteCollection();
        $collection->add(
            ...$routeCollector->getRoutes(
                VersionCommand::class,
                ListCommand::class,
                ListBashCommand::class
            )
        );

        $outputFactory = new OutputFactory();

        $container = new Container();
        $container->setSingleton(
            CliConfigContract::class,
            new CliConfig(namespace: self::APP_NAMESPACE, version: self::APP_VERSION)
        );
        $container->setSingleton(OutputFactoryContract::class, $outputFactory);
        $container->setSingleton(RouteCollectionContract::class, $collection);
        $container->setFromData(
            new ContainerData(
                callbacks: [
                    ListBashCommand::class => [CliServerServiceProvider::class, 'publishListBashCommand'],
                    ListCommand::class     => [CliServerServiceProvider::class, 'publishListCommand'],
                    VersionCommand::class  => [CliServerServiceProvider::class, 'publishVersionCommand'],
                ]
            )
        );

        $router = new Router(
            container: $container,
            collection: $collection,
            outputFactory: $outputFactory
        );

        return $router->dispatch(InputFactory::fromGlobals($argv, 'cli', 'list'));
    }

    /**
     * Join every message of an output into one string.
     */
    private function text(OutputContract $output): string
    {
        return implode(
            '',
            array_map(
                static fn (MessageContract $message): string => $message->getText(),
                $output->getMessages()
            )
        );
    }
}
