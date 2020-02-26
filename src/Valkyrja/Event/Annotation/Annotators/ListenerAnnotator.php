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

namespace Valkyrja\Event\Annotation\Annotators;

use InvalidArgumentException;
use ReflectionException;
use Valkyrja\Annotation\Annotators\Annotator;
use Valkyrja\Application\Application;
use Valkyrja\Event\Annotation\ListenerAnnotator as ListenerAnnotatorContract;
use Valkyrja\Event\Annotation\Models\Listener;
use Valkyrja\Event\Listener as ListenerContract;
use Valkyrja\Event\Models\Listener as ListenerModel;

/**
 * Class ListenerAnnotator.
 *
 * @author Melech Mizrachi
 */
class ListenerAnnotator extends Annotator implements ListenerAnnotatorContract
{
    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ListenerAnnotatorContract::class,
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
        $app->container()->setSingleton(
            ListenerAnnotatorContract::class,
            new static($app)
        );
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
            foreach ($this->filter()->methodsAnnotationsByType('Listener', $class) as $annotation) {
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
}
