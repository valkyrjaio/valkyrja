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

use Valkyrja\Annotations\AnnotationsImpl;
use Valkyrja\Application;
use Valkyrja\Container\CoreComponent;
use Valkyrja\Events\Listener as EventListener;
use Valkyrja\Support\Providers\Provides;

/**
 * Class ListenerAnnotations.
 *
 * @author Melech Mizrachi
 */
class ListenerAnnotationsImpl extends AnnotationsImpl implements ListenerAnnotations
{
    use Provides;

    /**
     * Get the events.
     *
     * @param string[] $classes The classes
     *
     * @throws \ReflectionException
     *
     * @return \Valkyrja\Events\Listener[]
     */
    public function getListeners(string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            /** @var \Valkyrja\Events\Annotations\Listener $annotation */
            foreach ($this->methodsAnnotationsType('Listener', $class) as $annotation) {
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
     * @param \Valkyrja\Events\Annotations\Listener $listener
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    protected function setListenerProperties(Listener $listener): void
    {
        $classReflection = $this->getClassReflection($listener->getClass());

        if ($listener->getMethod() || $classReflection->hasMethod('__construct')) {
            $methodReflection = $this->getMethodReflection(
                $listener->getClass(),
                $listener->getMethod() ?? '__construct'
            );
            $parameters       = $methodReflection->getParameters();

            // Set the dependencies
            $listener->setDependencies($this->getDependencies(...$parameters));
        }

        $listener->setMatches();
    }

    /**
     * Get a listener from a listener annotation.
     *
     * @param \Valkyrja\Events\Annotations\Listener $listener The listener annotation
     *
     * @return \Valkyrja\Events\Listener
     */
    protected function getListenerFromAnnotation(Listener $listener): EventListener
    {
        return (new EventListener())
            ->setEvent($listener->getEvent())
            ->setId($listener->getId())
            ->setName($listener->getName())
            ->setClass($listener->getClass())
            ->setProperty($listener->getProperty())
            ->setMethod($listener->getMethod())
            ->setStatic($listener->isStatic())
            ->setFunction($listener->getFunction())
            ->setMatches($listener->getMatches())
            ->setDependencies($listener->getDependencies())
            ->setArguments($listener->getArguments());
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CoreComponent::LISTENER_ANNOTATIONS,
        ];
    }

    /**
     * Bind the listener annotations.
     *
     * @param \Valkyrja\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::LISTENER_ANNOTATIONS,
            new static(
                $app->container()->getSingleton(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }
}
