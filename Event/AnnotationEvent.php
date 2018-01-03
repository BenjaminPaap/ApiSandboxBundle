<?php

namespace Bpa\ApiSandboxBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class AnnotationEvent
 */
class AnnotationEvent extends Event
{
    /**
     * @var array
     */
    private $annotations;

    /**
     * AnnotationEvent constructor.
     *
     * @param array $annotations
     */
    public function __construct(array $annotations)
    {
        $this->annotations = $annotations;
    }

    /**
     * @return array
     */
    public function getAnnotations()
    {
        return $this->annotations;
    }
}
