<?php

namespace Bpa\ApiSandboxBundle\Annotation;

/**
 * Class AbstractAnnotation
 */
abstract class AbstractAnnotation
{
    /**
     * Response constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $name => $attribute) {
            $setter = 'set'.ucfirst($name);

            if (method_exists($this, $setter)) {
                $this->$setter($attribute);
            }
        }
    }
}
