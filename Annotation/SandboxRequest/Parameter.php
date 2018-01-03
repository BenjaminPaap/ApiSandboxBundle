<?php

namespace Bpa\ApiSandboxBundle\Annotation\SandboxRequest;

use Bpa\ApiSandboxBundle\Annotation\AbstractAnnotation;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Annotation\Attributes({
 *     @Annotation\Attribute("name", type="string"),
 *     @Annotation\Attribute("type", type=@Annotation\Enum({Parameter::TYPE_STRING, Parameter::TYPE_INTEGER, Parameter::TYPE_ARRAY})),
 *     @Annotation\Attribute("required", type="boolean"),
 *     @Annotation\Attribute("children", type="array<Parameter>"),
 *     @Annotation\Attribute("value", type="mixed"),
 *     @Annotation\Attribute("format", type="string"),
 * })
 */
class Parameter extends AbstractAnnotation
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_ARRAY = 'array';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var boolean
     */
    private $required;

    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $value;

    /**
     * @var array
     */
    private $children;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param bool $required
     *
     * @return $this
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param array $children
     *
     * @return $this
     */
    public function setChildren(array $children)
    {
        $this->children = $children;

        return $this;
    }
}
