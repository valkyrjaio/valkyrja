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

namespace Valkyrja\Console\Annotators;

use ReflectionException;
use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\Filter;
use Valkyrja\Console\Annotations\Command;
use Valkyrja\Console\Annotator as Contract;
use Valkyrja\Console\Command as CommandContract;
use Valkyrja\Console\Models\Command as CommandModel;
use Valkyrja\Reflection\Reflector;

/**
 * Class CommandAnnotator.
 *
 * @author Melech Mizrachi
 */
class Annotator implements Contract
{
    /**
     * The filter.
     *
     * @var Filter
     */
    protected Filter $filter;

    /**
     * The reflector.
     *
     * @var Reflector
     */
    protected Reflector $reflector;

    /**
     * ContainerAnnotator constructor.
     *
     * @param Filter    $filter
     * @param Reflector $reflector
     */
    public function __construct(Filter $filter, Reflector $reflector)
    {
        $this->filter    = $filter;
        $this->reflector = $reflector;
    }

    /**
     * @inheritDoc
     */
    public function getCommands(string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            /** @var Command $annotation */
            foreach ($this->filter->classAnnotationsByType('Command', $class) as $annotation) {
                $this->setCommandProperties($annotation);
                // Set the annotation in the annotations list
                $annotations[] = $this->getCommandFromAnnotation($annotation);
            }

            // Get all the annotations for each class and iterate through them
            /** @var Command $annotation */
            foreach ($this->filter->methodsAnnotationsByType('Command', $class) as $annotation) {
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
        $class = $annotation->getClass();

        if (! $class) {
            return;
        }

        $classReflection = $this->reflector->getClassReflection($class);

        $method = $annotation->getMethod();

        if ($method !== null || $classReflection->hasMethod('__construct')) {
            $method ??= '__construct';

            /** @var non-empty-string $method */
            $methodReflection = $this->reflector->getMethodReflection($class, $method);

            // Set the dependencies
            $annotation->setDependencies($this->reflector->getDependencies($methodReflection));
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
}
