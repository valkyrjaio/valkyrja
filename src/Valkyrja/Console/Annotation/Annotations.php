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

namespace Valkyrja\Console\Annotation;

use ReflectionException;
use Valkyrja\Annotation\Filter\Contract\Filter;
use Valkyrja\Annotation\Model\Contract\Annotation;
use Valkyrja\Console\Annotation\Contract\Annotations as Contract;
use Valkyrja\Console\Model\Command as CommandModel;
use Valkyrja\Console\Model\Contract\Command as CommandContract;
use Valkyrja\Reflection\Contract\Reflection;

/**
 * Class CommandAnnotator.
 *
 * @author Melech Mizrachi
 */
class Annotations implements Contract
{
    /**
     * ContainerAnnotator constructor.
     */
    public function __construct(
        protected Filter $filter,
        protected Reflection $reflection
    ) {
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

        $classReflection = $this->reflection->forClass($class);

        $method = $annotation->getMethod();

        if ($method !== null || $classReflection->hasMethod('__construct')) {
            $method ??= '__construct';

            /** @var non-empty-string $method */
            $methodReflection = $this->reflection->forClassMethod($class, $method);

            // Set the dependencies
            $annotation->setDependencies($this->reflection->getDependencies($methodReflection));
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
