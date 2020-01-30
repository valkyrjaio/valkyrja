<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Events\Annotations;

use ReflectionException;
use Valkyrja\Annotations\AnnotationsParser;
use Valkyrja\Annotations\NativeAnnotations;
use Valkyrja\Application;
use Valkyrja\Events\Listener as EventListener;

/**
 * Class ListenerAnnotations.
 *
 * @author Melech Mizrachi
 */
class NativeListenerAnnotations extends NativeAnnotations implements ListenerAnnotations
{
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
     * @param Listener $listener
     *
     * @throws ReflectionException
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
     * @param Listener $listener The listener annotation
     *
     * @return EventListener
     */
    protected function getListenerFromAnnotation(Listener $listener): EventListener
    {
        $eventListener = new EventListener();

        $eventListener
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

        return $eventListener;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ListenerAnnotations::class,
        ];
    }

    /**
     * Bind the listener annotations.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            ListenerAnnotations::class,
            new static(
                $app->container()->getSingleton(AnnotationsParser::class)
            )
        );
    }
}
