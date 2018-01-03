<?php

namespace Bpa\ApiSandboxBundle\EventListener;

use Bpa\ApiSandboxBundle\Annotation\SandboxRequest;
use Bpa\ApiSandboxBundle\Event\AnnotationEvent;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class ApiDocListener
 */
class ApiDocListener
{
    /**
     * @param AnnotationEvent $event
     */
    public function onAnnotationsLoaded(AnnotationEvent $event)
    {
        $parameters = null;

        foreach ($event->getAnnotations() as $annotation) {
            if ($annotation instanceof ApiDoc) {
                $parameters = $this->extractParameters($annotation->getParameters());
            }
        }

        foreach ($event->getAnnotations() as $annotation) {
            if ($annotation instanceof SandboxRequest) {
                $annotation->setParameters($parameters);
            }
        }
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function extractParameters(array $parameters)
    {
        $sandboxParameters = [];

        foreach ($parameters as $name => $parameter) {
            $attributes = [
                'name' => $name,
            ];

            if (isset($parameter['required'])) {
                $attributes['required'] = $parameter['required'];
            }

            if (isset($parameter['dataType'])) {
                $attributes['type'] = $parameter['dataType'];
            }

            if (isset($parameter['format'])) {
                $attributes['format'] = $parameter['format'];
            }

            if (isset($parameter['children'])) {
                $attributes['type'] = SandboxRequest\Parameter::TYPE_ARRAY;
                $attributes['children'] = $this->extractParameters($parameter['children']);
            }

            $sandboxParameter = new SandboxRequest\Parameter($attributes);

            if (isset($attributes['children'])) {
                $sandboxParameter->setChildren($attributes['children']);
            }

            $sandboxParameters[] = $sandboxParameter;
        }

        return $sandboxParameters;
    }
}
