<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Annotation\Annotations;

use ReflectionException;
use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\Annotations\Annotations;
use Valkyrja\Annotation\AnnotationsParser;
use Valkyrja\Application\Application;
use Valkyrja\Console\Annotation\CommandAnnotations as CommandAnnotationsContract;
use Valkyrja\Console\Annotation\Models\Command;
use Valkyrja\Console\Command as CommandContract;
use Valkyrja\Console\Models\Command as CommandModel;

/**
 * Class CommandAnnotations.
 *
 * @author Melech Mizrachi
 */
class CommandAnnotations extends Annotations implements CommandAnnotationsContract
{
    /**
     * Get the commands.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return CommandContract[]
     */
    public function getCommands(string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            /** @var Command $annotation */
            foreach ($this->classAnnotationsType('Command', $class) as $annotation) {
                $this->setCommandProperties($annotation);
                // Set the annotation in the annotations list
                $annotations[] = $this->getCommandFromAnnotation($annotation);
            }

            // Get all the annotations for each class and iterate through them
            /** @var Command $annotation */
            foreach ($this->methodsAnnotationsType('Command', $class) as $annotation) {
                $this->setCommandProperties($annotation);
                // Set the annotation in the annotations list
                $annotations[] = $this->getCommandFromAnnotation($annotation);
            }
        }

        return $annotations;
    }

    /**
     * Set the properties for a command annotation.
     *
     * @param Annotation $annotation
     *
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setCommandProperties(Annotation $annotation): void
    {
        $classReflection = $this->getClassReflection($annotation->getClass());

        if ($annotation->getMethod() || $classReflection->hasMethod('__construct')) {
            $methodReflection = $this->getMethodReflection(
                $annotation->getClass(),
                $annotation->getMethod() ?? '__construct'
            );
            $parameters       = $methodReflection->getParameters();

            // Set the dependencies
            $annotation->setDependencies($this->getDependencies(...$parameters));
        }

        $annotation->setMatches();
    }

    /**
     * Get a command from a command annotation.
     *
     * @param Command $command The command annotation
     *
     * @return CommandContract
     */
    protected function getCommandFromAnnotation(Command $command): CommandContract
    {
        return CommandModel::fromArray($command->asArray());
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CommandAnnotationsContract::class,
        ];
    }

    /**
     * Bind the command annotations.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CommandAnnotationsContract::class,
            new static(
                $app->container()->getSingleton(AnnotationsParser::class)
            )
        );
    }
}
