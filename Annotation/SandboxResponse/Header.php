<?php

namespace Bpa\ApiSandboxBundle\Annotation\SandboxResponse;

use Bpa\ApiSandboxBundle\Annotation\AbstractAnnotation;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Annotation\Attributes({
 *     @Annotation\Attribute("name", type="string"),
 *     @Annotation\Attribute("value", type="string"),
 * })
 */
class Header extends AbstractAnnotation
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
