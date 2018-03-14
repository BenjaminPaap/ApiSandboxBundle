<?php

namespace Bpa\ApiSandboxBundle\Event;

use Bpa\ApiSandboxBundle\Annotation\SandboxRequest;
use Bpa\ApiSandboxBundle\Annotation\SandboxResponse;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event which holds a matched response
 */
class ResponseMatchEvent extends Event
{
    /**
     * @var SandboxRequest
     */
    private $request;

    /**
     * @var SandboxResponse
     */
    private $response;

    /**
     * ResponseEvent constructor.
     *
     * @param SandboxRequest $request
     * @param SandboxResponse $response
     */
    public function __construct(SandboxRequest $request, SandboxResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return SandboxRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param SandboxRequest $request
     *
     * @return $this
     */
    public function setRequest(SandboxRequest $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return SandboxResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param SandboxResponse $response
     *
     * @return $this
     */
    public function setResponse(SandboxResponse $response)
    {
        $this->response = $response;

        return $this;
    }
}
