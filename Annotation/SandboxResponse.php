<?php

namespace Bpa\ApiSandboxBundle\Annotation;

use Bpa\ApiSandboxBundle\Annotation\SandboxRequest\Parameter;
use Bpa\ApiSandboxBundle\Annotation\SandboxResponse\Header;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Annotation\Target({"METHOD"})
 * @Annotation\Attributes({
 *     @Annotation\Attribute("statusCode", type="integer"),
 *     @Annotation\Attribute("content", type="string"),
 *     @Annotation\Attribute("type", type=@Annotation\Enum({SandboxResponse::TYPE_JSON, SandboxResponse::TYPE_XML})),
 *     @Annotation\Attribute("headers", type="array<Bpa\ApiSandboxBundle\Annotation\SandboxResponse\Header>"),
 *     @Annotation\Attribute("parameters", type="array<Bpa\ApiSandboxBundle\Annotation\SandboxRequest\Parameter>"),
 * })
 */
class SandboxResponse extends AbstractAnnotation
{
    const TYPE_JSON = 'JSON';
    const TYPE_XML = 'XML';

    /**
     * @var array|Header[]
     */
    private $headers = [];

    /**
     * @var array|Parameter[]
     */
    private $parameters = [];

    /**
     * @var integer
     */
    private $statusCode = 200;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $type = self::TYPE_JSON;

    /**
     * @return array|Header[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array|Header[] $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

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
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

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
}
