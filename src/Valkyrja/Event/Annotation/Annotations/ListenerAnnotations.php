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

namespace Valkyrja\Event\Annotation\Annotations;

use ReflectionException;
use Valkyrja\Annotation\Annotations\Annotations;
use Valkyrja\Annotation\AnnotationsParser;
use Valkyrja\Application\Application;
use Valkyrja\Event\Annotation\ListenerAnnotations as ListenerAnnotationsContract;
use Valkyrja\Event\Annotation\Models\Listener;
use Valkyrja\Event\Listener as ListenerContract;
use Valkyrja\Event\Models\Listener as ListenerModel;

/**
 * Class ListenerAnnotations.
 *
 * @author Melech Mizrachi
 */
class ListenerAnnotations extends Annotations implements ListenerAnnotationsContract
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
            /** @var \Valkyrja\Event\Annotation\Models\Listener $annotation */
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
     * @param \Valkyrja\Event\Annotation\Models\Listener $listener
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
     * @return ListenerContract
     */
    protected function getListenerFromAnnotation(Listener $listener): ListenerContract
    {
        return ListenerModel::fromArray($listener->asArray());
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ListenerAnnotationsContract::class,
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
            ListenerAnnotationsContract::class,
            new static(
                $app->container()->getSingleton(AnnotationsParser::class)
            )
        );
    }
}
