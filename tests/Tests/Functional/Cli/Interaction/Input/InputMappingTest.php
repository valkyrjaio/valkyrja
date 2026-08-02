<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Functional\Cli\Interaction\Input;

use PHPUnit\Framework\Attributes\DataProvider;
use Valkyrja\Cli\Interaction\Argument\Contract\ArgumentContract;
use Valkyrja\Cli\Interaction\Enum\OptionType;
use Valkyrja\Cli\Interaction\Input\Factory\InputFactory;
use Valkyrja\Cli\Interaction\Option\Contract\OptionContract;
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionInvalidEmptyValueException;
use Valkyrja\Cli\Interaction\Throwable\Exception\CliInteractionInvalidNonEmptyValueException;
use Valkyrja\Tests\Functional\Abstract\TestCase;

use function array_map;
use function array_values;

/**
 * Message-mapping fidelity for an incoming CLI command.
 *
 * Asserts that an argv-style array lands on the framework's own Input,
 * Argument, and Option objects exactly as spelled, independent of routing.
 */
final class InputMappingTest extends TestCase
{
    /** @var non-empty-string */
    private const string DEFAULT_CALLER = 'valkyrja';

    /** @var non-empty-string */
    private const string DEFAULT_COMMAND = 'list';

    /**
     * Every option spelling the factory supports.
     *
     * @return array<string, array{non-empty-string[], list<array{string, string, OptionType}>}>
     */
    public static function provideOptionSpellings(): array
    {
        return [
            'long with value'         => [
                ['valkyrja', 'cmd', '--name=value'],
                [['name', 'value', OptionType::LONG]],
            ],
            'long without value'      => [
                ['valkyrja', 'cmd', '--verbose'],
                [['verbose', '', OptionType::LONG]],
            ],
            'long with empty value'   => [
                ['valkyrja', 'cmd', '--name='],
                [['name', '', OptionType::LONG]],
            ],
            'short without value'     => [
                ['valkyrja', 'cmd', '-v'],
                [['v', '', OptionType::SHORT]],
            ],
            'short with value'        => [
                ['valkyrja', 'cmd', '-n=value'],
                [['n', 'value', OptionType::SHORT]],
            ],
            'bundled short flags'     => [
                ['valkyrja', 'cmd', '-abc'],
                [
                    ['a', '', OptionType::SHORT],
                    ['b', '', OptionType::SHORT],
                    ['c', '', OptionType::SHORT],
                ],
            ],
            'repeated long option'    => [
                ['valkyrja', 'cmd', '--tag=one', '--tag=two'],
                [
                    ['tag', 'one', OptionType::LONG],
                    ['tag', 'two', OptionType::LONG],
                ],
            ],
            'mixed long and short'    => [
                ['valkyrja', 'cmd', '--name=value', '-v', '-ab'],
                [
                    ['name', 'value', OptionType::LONG],
                    ['v', '', OptionType::SHORT],
                    ['a', '', OptionType::SHORT],
                    ['b', '', OptionType::SHORT],
                ],
            ],
            'value containing equals' => [
                ['valkyrja', 'cmd', '--expr=a=b'],
                [['expr', 'a=b', OptionType::LONG]],
            ],
        ];
    }

    /**
     * Spellings the factory rejects.
     *
     * @return array<string, array{non-empty-string[], class-string}>
     */
    public static function provideRejectedSpellings(): array
    {
        return [
            'empty long option name'   => [
                ['valkyrja', 'cmd', '--=value'],
                CliInteractionInvalidNonEmptyValueException::class,
            ],
            'bundled short with value' => [
                ['valkyrja', 'cmd', '-abc=value'],
                CliInteractionInvalidEmptyValueException::class,
            ],
        ];
    }

    /**
     * @param ArgumentContract[] $arguments
     *
     * @return list<string>
     */
    private static function argumentValues(array $arguments): array
    {
        return array_values(
            array_map(
                static fn (ArgumentContract $argument): string => $argument->getValue(),
                $arguments
            )
        );
    }

    /**
     * @param OptionContract[] $options
     *
     * @return list<array{string, string, OptionType}>
     */
    private static function optionTuples(array $options): array
    {
        return array_values(
            array_map(
                static fn (OptionContract $option): array => [
                    $option->getName(),
                    $option->getValue(),
                    $option->getType(),
                ],
                $options
            )
        );
    }

    /**
     * The first argv entry becomes the caller and the second becomes the command name.
     */
    public function testCallerAndCommandNameMapFromArgv(): void
    {
        $input = InputFactory::fromGlobals(
            ['bin/valkyrja', 'app:version'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        self::assertSame(expected: 'bin/valkyrja', actual: $input->getCaller());
        self::assertSame(expected: 'app:version', actual: $input->getCommandName());
        self::assertSame(expected: [], actual: $input->getArguments());
        self::assertSame(expected: [], actual: $input->getOptions());
    }

    /**
     * The supplied defaults stand in when argv carries no command name.
     */
    public function testDefaultsApplyWhenArgvIsBare(): void
    {
        $input = InputFactory::fromGlobals(['bin/valkyrja'], self::DEFAULT_CALLER, self::DEFAULT_COMMAND);

        self::assertSame(expected: 'bin/valkyrja', actual: $input->getCaller());
        self::assertSame(expected: self::DEFAULT_COMMAND, actual: $input->getCommandName());

        $empty = InputFactory::fromGlobals([], self::DEFAULT_CALLER, self::DEFAULT_COMMAND);

        self::assertSame(expected: self::DEFAULT_CALLER, actual: $empty->getCaller());
        self::assertSame(expected: self::DEFAULT_COMMAND, actual: $empty->getCommandName());
    }

    /**
     * Everything after the command name that is not an option becomes a positional
     * argument, in argv order.
     */
    public function testPositionalArgumentsMapInOrder(): void
    {
        $input = InputFactory::fromGlobals(
            ['valkyrja', 'app:copy', 'source.txt', 'target.txt', 'third'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        self::assertSame(expected: 'app:copy', actual: $input->getCommandName());
        self::assertSame(
            expected: ['source.txt', 'target.txt', 'third'],
            actual: self::argumentValues($input->getArguments())
        );
    }

    /**
     * Options and positional arguments interleave without disturbing each other's order.
     */
    public function testOptionsAndArgumentsInterleave(): void
    {
        $input = InputFactory::fromGlobals(
            ['valkyrja', 'app:copy', 'source.txt', '--force', 'target.txt', '-v'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        self::assertSame(
            expected: ['source.txt', 'target.txt'],
            actual: self::argumentValues($input->getArguments())
        );
        self::assertSame(
            expected: [['force', '', OptionType::LONG], ['v', '', OptionType::SHORT]],
            actual: self::optionTuples($input->getOptions())
        );
    }

    /**
     * @param non-empty-string[]                      $args
     * @param list<array{string, string, OptionType}> $expected
     */
    #[DataProvider('provideOptionSpellings')]
    public function testOptionSpellingsMapOntoOptions(array $args, array $expected): void
    {
        $input = InputFactory::fromGlobals($args, self::DEFAULT_CALLER, self::DEFAULT_COMMAND);

        self::assertSame(expected: 'cmd', actual: $input->getCommandName());
        self::assertSame(expected: $expected, actual: self::optionTuples($input->getOptions()));
    }

    /**
     * @param non-empty-string[] $args
     * @param class-string       $expected
     */
    #[DataProvider('provideRejectedSpellings')]
    public function testRejectedSpellingsThrow(array $args, string $expected): void
    {
        $this->expectException($expected);

        InputFactory::fromGlobals($args, self::DEFAULT_CALLER, self::DEFAULT_COMMAND);
    }

    /**
     * hasValue() reflects whether a value was spelled out.
     */
    public function testOptionValuePresenceMapsFromSpelling(): void
    {
        $input = InputFactory::fromGlobals(
            ['valkyrja', 'cmd', '--with=value', '--without'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        [$with, $without] = array_values($input->getOptions());

        self::assertTrue($with->hasValue());
        self::assertSame(expected: 'value', actual: $with->getValue());
        self::assertFalse($without->hasValue());
        self::assertSame(expected: '', actual: $without->getValue());
    }

    /**
     * A repeated option is preserved once per occurrence and looked up by name.
     */
    public function testRepeatedOptionsAreAllRetrievable(): void
    {
        $input = InputFactory::fromGlobals(
            ['valkyrja', 'cmd', '--tag=one', '--tag=two', '--other=x'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        self::assertTrue($input->hasOption('tag'));
        self::assertTrue($input->hasOption('other'));
        self::assertFalse($input->hasOption('missing'));
        self::assertSame(expected: [], actual: $input->getOption('missing'));

        $tags = array_values($input->getOption('tag'));

        self::assertCount(expectedCount: 2, haystack: $tags);
        self::assertSame(expected: 'one', actual: $tags[0]->getValue());
        self::assertSame(expected: 'two', actual: $tags[1]->getValue());
    }

    /**
     * An option spelled before the command name consumes that slot, so the default
     * command name stands and the later bare token becomes a positional argument.
     */
    public function testOptionBeforeCommandNameLeavesTheDefaultCommandName(): void
    {
        $input = InputFactory::fromGlobals(
            ['valkyrja', '--verbose', 'app:version'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        self::assertSame(expected: self::DEFAULT_COMMAND, actual: $input->getCommandName());
        self::assertSame(expected: ['app:version'], actual: self::argumentValues($input->getArguments()));
        self::assertTrue($input->hasOption('verbose'));
    }

    /**
     * A space-separated option value is not attached to the option — it lands as a
     * positional argument, so only the `--opt=value` spelling carries a value.
     */
    public function testSpaceSeparatedOptionValueBecomesAnArgument(): void
    {
        $input = InputFactory::fromGlobals(
            ['valkyrja', 'cmd', '--name', 'value'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        self::assertSame(expected: ['value'], actual: self::argumentValues($input->getArguments()));
        self::assertSame(
            expected: [['name', '', OptionType::LONG]],
            actual: self::optionTuples($input->getOptions())
        );
    }

    /**
     * A bare `--` ends option parsing: it is consumed, and everything after it is an
     * operand no matter how many dashes it starts with.
     */
    public function testDoubleDashEndsOptionParsing(): void
    {
        $input = InputFactory::fromGlobals(
            ['valkyrja', 'cmd', '--real', '--', '--not-an-option', '-x', 'plain'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        self::assertSame(expected: 'cmd', actual: $input->getCommandName());
        self::assertSame(
            expected: ['--not-an-option', '-x', 'plain'],
            actual: self::argumentValues($input->getArguments())
        );
        self::assertSame(
            expected: [['real', '', OptionType::LONG]],
            actual: self::optionTuples($input->getOptions())
        );
    }

    /**
     * The `--` itself never becomes an operand, but a second one does.
     */
    public function testSecondDoubleDashIsAnOperand(): void
    {
        $input = InputFactory::fromGlobals(
            ['valkyrja', 'cmd', '--', '--', 'tail'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        self::assertSame(expected: ['--', 'tail'], actual: self::argumentValues($input->getArguments()));
        self::assertSame(expected: [], actual: $input->getOptions());
    }

    /**
     * A lone `-` names standard input by convention, so it is an operand rather than an
     * option — both before and after an end-of-options marker.
     */
    public function testLoneDashIsAnOperand(): void
    {
        $input = InputFactory::fromGlobals(
            ['valkyrja', 'cmd', '-', '--verbose', '--', '-'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        self::assertSame(expected: ['-', '-'], actual: self::argumentValues($input->getArguments()));
        self::assertSame(
            expected: [['verbose', '', OptionType::LONG]],
            actual: self::optionTuples($input->getOptions())
        );
    }

    /**
     * A `--` spelled in the command-name slot is still consumed, so the default command
     * name stands and the following token becomes an operand.
     */
    public function testDoubleDashInTheCommandNameSlot(): void
    {
        $input = InputFactory::fromGlobals(
            ['valkyrja', '--', 'app:version'],
            self::DEFAULT_CALLER,
            self::DEFAULT_COMMAND
        );

        self::assertSame(expected: self::DEFAULT_COMMAND, actual: $input->getCommandName());
        self::assertSame(expected: ['app:version'], actual: self::argumentValues($input->getArguments()));
    }

    /**
     * A lone `-` in the command-name slot fills it, since it is an operand.
     */
    public function testLoneDashInTheCommandNameSlot(): void
    {
        $input = InputFactory::fromGlobals(['valkyrja', '-'], self::DEFAULT_CALLER, self::DEFAULT_COMMAND);

        self::assertSame(expected: '-', actual: $input->getCommandName());
        self::assertSame(expected: [], actual: $input->getArguments());
    }
}
