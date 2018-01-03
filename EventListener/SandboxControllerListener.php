<?php

namespace Bpa\ApiSandboxBundle\EventListener;

use Bpa\ApiSandboxBundle\Service\ControllerService;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Returns a response for any annotated controller
 */
class SandboxControllerListener
{
    /**
     * @var ControllerService
     */
    private $service;

    /**
     * SandboxControllerListener constructor.
     *
     * @param ControllerService $service
     */
    public function __construct(ControllerService $service)
    {
        $this->service = $service;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        list($controller, $method) = $event->getController();

        $controller = $this->service->getSandboxController($controller, $method);

        $event->setController($controller);
    }
}
