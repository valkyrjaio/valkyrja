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

namespace Valkyrja\Event\Annotation\Annotators;

use InvalidArgumentException;
use ReflectionException;
use Valkyrja\Annotation\Filter;
use Valkyrja\Event\Annotation\Annotator as Contract;
use Valkyrja\Event\Annotation\Models\Listener;
use Valkyrja\Event\Listener as ListenerContract;
use Valkyrja\Event\Models\Listener as ListenerModel;
use Valkyrja\Reflection\Reflector;

/**
 * Class ListenerAnnotator.
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
     * Get the events.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return Listener[]
     */
    public function getListeners(string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            /** @var Listener $annotation */
            foreach ($this->filter->methodsAnnotationsByType('Listener', $class) as $annotation) {
                $this->setListenerProperties($annotation);
                // Set the annotation in the annotations list
                $annotations[] = $this->getListenerFromAnnotation($annotation);
            }
        }

        return $annotations;
    }

    /**
     * Set the properties for a listener annotation.
     *
     * @param Listener $listener
     *
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setListenerProperties(Listener $listener): void
    {
        if (! $listener->getClass()) {
            throw new InvalidArgumentException('Invalid class defined in listener.');
        }

        $classReflection = $this->reflector->getClassReflection($listener->getClass());

        if ($listener->getMethod() || $classReflection->hasMethod('__construct')) {
            $methodReflection = $this->reflector->getMethodReflection(
                $listener->getClass(),
                $listener->getMethod() ?? '__construct'
            );

            // Set the dependencies
            $listener->setDependencies($this->reflector->getDependencies($methodReflection));
        }

        $listener->setMatches();
    }

    /**
     * Get a listener from a listener annotation.
     *
     * @param Listener $listener The listener annotation
     *
     * @return ListenerContract
     */
    protected function getListenerFromAnnotation(Listener $listener): ListenerContract
    {
        return ListenerModel::fromArray($listener->__toArray());
    }
}
