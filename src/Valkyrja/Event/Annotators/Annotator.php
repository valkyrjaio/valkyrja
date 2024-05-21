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

namespace Valkyrja\Event\Annotators;

use InvalidArgumentException;
use ReflectionException;
use Valkyrja\Annotation\Filter;
use Valkyrja\Event\Annotations\Listener;
use Valkyrja\Event\Annotator as Contract;
use Valkyrja\Event\Listener as ListenerContract;
use Valkyrja\Event\Models\Listener as ListenerModel;
use Valkyrja\Reflection\Contract\Reflection;

/**
 * Class ListenerAnnotator.
 *
 * @author Melech Mizrachi
 */
class Annotator implements Contract
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
     *
     * @throws ReflectionException
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
        if (! ($class = $listener->getClass())) {
            throw new InvalidArgumentException('Invalid class defined in listener.');
        }

        $classReflection = $this->reflection->forClass($class);

        if (($method = $listener->getMethod()) !== null || $classReflection->hasMethod('__construct')) {
            $method ??= '__construct';

            /** @var non-empty-string $method */
            $methodReflection = $this->reflection->forClassMethod($class, $method);

            // Set the dependencies
            $listener->setDependencies($this->reflection->getDependencies($methodReflection));
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
        return ListenerModel::fromArray($listener->asArray());
    }
}
