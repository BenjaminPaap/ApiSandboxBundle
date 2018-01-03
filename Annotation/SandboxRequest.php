<?php

namespace Bpa\ApiSandboxBundle\Annotation;

use Bpa\ApiSandboxBundle\Annotation\SandboxRequest\Parameter;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Annotation\Target({"METHOD"})
 * @Annotation\Attributes({
 *     @Annotation\Attribute("parameters", type="array<Bpa\ApiSandboxBundle\Annotation\SandboxRequest\Parameter>"),
 *     @Annotation\Attribute("responses", type="array<Bpa\ApiSandboxBundle\Annotation\SandboxResponse>")
 * })
 */
class SandboxRequest extends AbstractAnnotation
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var array
     */
    private $responses = [];

    /**
     * @param Parameter $parameter
     *
     * @return $this
     */
    public function addParameter(Parameter $parameter)
    {
        if (!in_array($parameter, $this->parameters)) {
            $this->parameters[] = $parameter;
        }

        return $this;
    }

    /**
     * @return array|Parameter[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return array|SandboxResponse[]
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param array $responses
     *
     * @return $this
     */
    public function setResponses(array $responses)
    {
        $this->responses = $responses;

        return $this;
    }
}
