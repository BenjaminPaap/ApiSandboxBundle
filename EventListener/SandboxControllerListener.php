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
     * @var boolean
     */
    private $enabled;

    /**
     * SandboxControllerListener constructor.
     *
     * @param boolean           $enabled
     * @param ControllerService $service
     */
    public function __construct($enabled, ControllerService $service)
    {
        $this->enabled = $enabled;
        $this->service = $service;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        list($controller, $method) = $event->getController();

        if ($this->enabled) {
            $controller = $this->service->getSandboxController($controller, $method);

            $event->setController($controller);
        }
    }
}
