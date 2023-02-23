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

namespace Valkyrja\Test\Suites;

use Valkyrja\Test\Enums\Argument;
use Valkyrja\Test\Output\Output;
use Valkyrja\Test\Output\Outputters\EchoOutput;
use Valkyrja\Test\Output\Results;
use Valkyrja\Test\Suite as Contract;

/**
 * Class Suite.
 *
 * @author Melech Mizrachi
 */
class Suite implements Contract
{
    public function __construct(
        protected Output $output = new EchoOutput(),
        protected Results $results = new Results\Results(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function run(array $args = null): void
    {
        $args    ??= $this->getServerArgs();
        $results = $this->results;
        $files   = $this->getFilesFromArgs($args);

        $this->output->title();
        $this->output->sectionSpacing();
    }

    /**
     * Get the server arguments.
     */
    protected function getServerArgs(): array
    {
        global $argv;

        return $argv;
    }

    /**
     * Get the files from an array of arguments.
     *
     * @param string[] $args The arguments
     *
     * @return string[]
     */
    protected function getFilesFromArgs(array $args): array
    {
        return $this->getFilteredArgs(Argument::file->name, $args);
    }

    /**
     * Get a filtered array of arguments from an array of arguments given a specific name.
     *
     * @param string[] $args The arguments
     *
     * @return string[]
     */
    protected function getFilteredArgs(string $argName, array $args): array
    {
        $filteredArgs = [];

        foreach ($args as $arg) {
            if (str_contains($arg, "--{$argName}")) {
                $filteredArgs[] = str_replace("--{$argName}=", '', $arg);
            }
        }

        return $filteredArgs;
    }
}
