<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Annotations;

use Valkyrja\Annotations\Annotation;
use Valkyrja\Annotations\NativeAnnotations;
use Valkyrja\Annotations\AnnotationsParser;
use Valkyrja\Application;
use Valkyrja\Console\Command as ConsoleCommand;
use Valkyrja\Support\Providers\Provides;

/**
 * Class CommandAnnotations.
 *
 * @author Melech Mizrachi
 */
class NativeCommandAnnotations extends NativeAnnotations implements CommandAnnotations
{
    use Provides;

    /**
     * Get the commands.
     *
     * @param string[] $classes The classes
     *
     * @throws \ReflectionException
     *
     * @return \Valkyrja\Console\Command[]
     */
    public function getCommands(string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            /** @var \Valkyrja\Console\Annotations\Command $annotation */
            foreach ($this->classAnnotationsType(
                'Command',
                $class
            ) as $annotation) {
                $this->setCommandProperties($annotation);
                // Set the annotation in the annotations list
                $annotations[] = $this->getCommandFromAnnotation($annotation);
            }

            // Get all the annotations for each class and iterate through them
            /** @var \Valkyrja\Console\Annotations\Command $annotation */
            foreach ($this->methodsAnnotationsType(
                'Command',
                $class
            ) as $annotation) {
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
     * @throws \ReflectionException
     *
     * @return void
     */
    protected function setCommandProperties(Annotation $annotation): void
    {
        $classReflection = $this->getClassReflection($annotation->getClass());

        if ($annotation->getMethod() || $classReflection->hasMethod(
                '__construct'
            )
        ) {
            $methodReflection = $this->getMethodReflection(
                $annotation->getClass(),
                $annotation->getMethod() ?? '__construct'
            );
            $parameters       = $methodReflection->getParameters();

            // Set the dependencies
            $annotation->setDependencies(
                $this->getDependencies(...$parameters)
            );
        }

        $annotation->setMatches();
    }

    /**
     * Get a command from a command annotation.
     *
     * @param Command $command The command annotation
     *
     * @return \Valkyrja\Console\Command
     */
    protected function getCommandFromAnnotation(
        Command $command
    ): ConsoleCommand {
        $consoleCommand = new ConsoleCommand();

        $consoleCommand
            ->setPath($command->getRegex())
            ->setRegex($command->getRegex())
            ->setParams($command->getParams())
            ->setSegments($command->getSegments())
            ->setDescription($command->getDescription())
            ->setId($command->getId())
            ->setName($command->getName())
            ->setClass($command->getClass())
            ->setProperty($command->getProperty())
            ->setMethod($command->getMethod())
            ->setStatic($command->isStatic())
            ->setFunction($command->getFunction())
            ->setMatches($command->getMatches())
            ->setDependencies($command->getDependencies())
            ->setArguments($command->getArguments());

        return $consoleCommand;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CommandAnnotations::class,
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
            CommandAnnotations::class,
            new static(
                $app->container()->getSingleton(AnnotationsParser::class)
            )
        );
    }
}
