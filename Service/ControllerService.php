<?php

namespace Bpa\ApiSandboxBundle\Service;

use Bpa\ApiSandboxBundle\Annotation\SandboxRequest;
use Bpa\ApiSandboxBundle\Annotation\SandboxResponse;
use Bpa\ApiSandboxBundle\Event\AnnotationEvent;
use Bpa\ApiSandboxBundle\Event\ApiSandboxEvents;
use Bpa\ApiSandboxBundle\Event\ResponseMatchEvent;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Creates a controller to replace the original controller
 */
class ControllerService
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var FileLocatorInterface
     */
    private $fileLocator;

    /**
     * @var boolean
     */
    private $forceSandbox;

    /**
     * ControllerService constructor.
     *
     * @param RequestStack             $requestStack
     * @param AnnotationReader         $annotationReader
     * @param EventDispatcherInterface $dispatcher
     * @param FileLocatorInterface     $fileLocator
     * @param boolean                  $forceSandbox
     */
    public function __construct(
        RequestStack $requestStack,
        AnnotationReader $annotationReader,
        EventDispatcherInterface $dispatcher,
        FileLocatorInterface $fileLocator,
        $forceSandbox
    ) {
        $this->requestStack = $requestStack;
        $this->annotationReader = $annotationReader;
        $this->dispatcher = $dispatcher;
        $this->fileLocator = $fileLocator;
        $this->forceSandbox = $forceSandbox;
    }

    /**
     * @param object $controller
     * @param string $method
     *
     * @return callable
     */
    public function getSandboxController($controller, $method)
    {
        if (null === $annotation = $this->getMatchingResponseAnnotation($controller, $method)) {
            if ($this->forceSandbox) {
                throw new HttpException(
                    Response::HTTP_BAD_REQUEST,
                    'Could not find any matching sandbox response'
                );
            } else {
                return [$controller, $method];
            }
        }

        if (null !== $content = $annotation->getContent()) {
            if (substr($content, 0, 1) == '@') {
                $path = $this->fileLocator->locate($content);
                $content = file_get_contents($path);
            }
        }

        $headers = [];
        foreach ($annotation->getHeaders() as $header) {
            $headers[$header->getName()] = $header->getValue();
        }

        switch ($annotation->getType()) {
            case SandboxResponse::TYPE_XML:
                $class = Response::class;
                break;

            default:
                $class = JsonResponse::class;
                $content = json_decode($content, true);
                break;
        }

        $response = new $class($content, $annotation->getStatusCode(), $headers);

        return function() use ($response) {
            return $response;
        };
    }

    /**
     * @param object $controller
     * @param string $method
     *
     * @return null|SandboxResponse
     */
    private function getMatchingResponseAnnotation($controller, $method)
    {
        $method = new \ReflectionMethod($controller, $method);

        $annotations = $this->annotationReader->getMethodAnnotations($method);

        $event = new AnnotationEvent($annotations);
        $this->dispatcher->dispatch(ApiSandboxEvents::ANNOTATIONS_LOADED, $event);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof SandboxRequest) {
                foreach ($annotation->getResponses() as $response) {
                    if ($this->isResponseMatching($annotation, $response)) {
                        return $response;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param SandboxRequest  $request
     * @param SandboxResponse $response
     *
     * @return bool
     */
    private function isResponseMatching(SandboxRequest $request, SandboxResponse $response)
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        /** @var SandboxRequest\Parameter[] $parameters */
        $parameters = $this->mergeParameters($request, $response);

        foreach ($parameters as $parameter) {
            $value = $currentRequest->get($parameter->getName(), null);

            if ($parameter->isRequired() && null === $value) {
                throw new HttpException(
                    Response::HTTP_BAD_REQUEST,
                    sprintf('Parameter "%s" is missing.', $parameter->getName())
                );
            }

            if (null !== $parameter->getValue()) {
                if ($value != $parameter->getValue()) {
                    return false;
                }
            }

            if ((null !== $format = $parameter->getFormat()) && null !== $value) {
                if (!preg_match('@'.$format.'@', $value)) {
                    throw new HttpException(
                        Response::HTTP_BAD_REQUEST,
                        sprintf(
                            'Value "%s" for parameter "%s" does not match format "%s"',
                            $value,
                            $parameter->getName(),
                            $value
                        )
                    );
                }
            }
        }

        $event = new ResponseMatchEvent($request, $response);
        $this->dispatcher->dispatch(ApiSandboxEvents::RESPONSE_MATCH, $event);

        return true;
    }

    /**
     * @param SandboxRequest  $request
     * @param SandboxResponse $response
     *
     * @return array
     */
    private function mergeParameters(SandboxRequest $request, SandboxResponse $response)
    {
        $parameters = [];

        // Get the default parameters from the request
        foreach ($request->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = $parameter;
        }

        // Override any default parameters from the request with those from the response
        foreach ($response->getParameters() as $parameter) {
            if (isset($parameters[$parameter->getName()])) {
                $original = $parameters[$parameter->getName()];
                $methods = get_class_methods($parameter);

                foreach ($methods as $method) {
                    if (preg_match('@(?<getter>get(?<name>.+))@', $method, $matches)) {
                        $setter = 'set'.ucfirst($matches['name']);
                        $getter = $matches['getter'];

                        if (null !== $value = $parameter->$getter()) {
                            $original->$setter($value);
                        }
                    }
                }
            } else {
                $parameters[$parameter->getName()] = $parameter;
            }
        }

        return $parameters;
    }
}
