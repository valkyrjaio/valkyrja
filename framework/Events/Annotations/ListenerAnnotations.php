<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Events\Annotations;

use Valkyrja\Annotations\Annotations;
use Valkyrja\Dispatcher\Dispatch;

/**
 * Class ListenerAnnotations
 *
 * @package Valkyrja\Events\Annotations
 *
 * @author  Melech Mizrachi
 */
class ListenerAnnotations extends Annotations
{
    /**
     * Get the events.
     *
     * @param string[] $classes The classes
     *
     * @return \Valkyrja\Events\Listener[]
     *
     * @throws \ReflectionException
     */
    public function getListeners(string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            /** @var \Valkyrja\Dispatcher\Dispatch $annotation */
            foreach ($this->methodsAnnotationsType('Listener', $class) as $annotation) {
                $this->setListenerProperties($annotation);
                // Set the annotation in the annotations list
                $annotations[] = $annotation;
            }
        }

        return $annotations;
    }

    /**
     * Set the properties for a service annotation.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    protected function setListenerProperties(Dispatch $dispatch): void
    {
        $parameters = $this->getMethodReflection($dispatch->getClass(), $dispatch->getMethod() ?? '__construct')
                           ->getParameters();

        // Set the dependencies
        $dispatch->setDependencies($this->getDependencies(...$parameters));

        // Set the type to null (we already know it's a listener)
        $dispatch->setType();
    }
}
