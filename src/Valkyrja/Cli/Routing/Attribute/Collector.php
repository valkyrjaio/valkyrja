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

namespace Valkyrja\Cli\Routing\Attribute;

use ReflectionException;
use Valkyrja\Attribute\Contract\Attributes;
use Valkyrja\Cli\Routing\Attribute\Command as Attribute;
use Valkyrja\Cli\Routing\Attribute\Contract\Collector as Contract;
use Valkyrja\Cli\Routing\Data\Command as Model;
use Valkyrja\Cli\Routing\Data\Contract\Command;
use Valkyrja\Reflection\Contract\Reflection;

/**
 * Class Collector.
 *
 * @author Melech Mizrachi
 */
class Collector implements Contract
{
    public function __construct(
        protected Attributes $attributes,
        protected Reflection $reflection,
    ) {
    }

    /**
     * Get the commands.
     *
     * @param class-string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return Command[]
     */
    public function getCommands(string ...$classes): array
    {
        $commands = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            /** @var Attribute[] $attributes */
            $attributes = $this->attributes->forClassAndMembers($class, Attribute::class);

            // Get all the attributes for each class and iterate through them
            foreach ($attributes as $attribute) {
                $commands[] = $this->getCommandFromAttribute($attribute);
            }
        }

        return $commands;
    }

    /**
     * Get a command from an attribute.
     *
     * @param Command $attribute The attribute
     *
     * @throws ReflectionException
     *
     * @return Command
     */
    protected function getCommandFromAttribute(Command $attribute): Command
    {
        $dispatch = $attribute->getDispatch();

        $methodReflection = $this->reflection->forClassMethod($dispatch->getClass(), $dispatch->getMethod());
        $dependencies     = $this->reflection->getDependencies($methodReflection);

        return (new Model(
            name: $attribute->getName(),
            description: $attribute->getDescription(),
            helpText: $attribute->getHelpText(),
            dispatch: $attribute->getDispatch()->withDependencies($dependencies)
        ))->withArguments(...$attribute->getArguments())
          ->withOptions(...$attribute->getOptions());
    }
}
