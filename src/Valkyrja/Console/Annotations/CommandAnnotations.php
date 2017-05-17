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

use Valkyrja\Annotations\Annotations;
use Valkyrja\Dispatcher\Dispatch;

/**
 * Class CommandAnnotations.
 *
 * @author Melech Mizrachi
 */
class CommandAnnotations extends Annotations
{
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
            /** @var \Valkyrja\Dispatcher\Dispatch $annotation */
            foreach ($this->classAnnotationsType('Command', $class) as $annotation) {
                $this->setCommandProperties($annotation);
                // Set the annotation in the annotations list
                $annotations[] = $annotation;
            }

            // Get all the annotations for each class and iterate through them
            /** @var \Valkyrja\Dispatcher\Dispatch $annotation */
            foreach ($this->methodsAnnotationsType('Command', $class) as $annotation) {
                $this->setCommandProperties($annotation);
                // Set the annotation in the annotations list
                $annotations[] = $annotation;
            }
        }

        return $annotations;
    }

    /**
     * Set the properties for a command annotation.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    protected function setCommandProperties(Dispatch $dispatch): void
    {
        $classReflection = $this->getClassReflection($dispatch->getClass());

        if ($dispatch->getMethod() || $classReflection->hasMethod('__construct')) {
            $methodReflection = $this->getMethodReflection(
                $dispatch->getClass(),
                $dispatch->getMethod() ?? '__construct'
            );
            $parameters       = $methodReflection->getParameters();

            // Set the dependencies
            $dispatch->setDependencies($this->getDependencies(...$parameters));
        }

        $dispatch->setMatches();
        $dispatch->setAnnotationArguments();
    }
}
