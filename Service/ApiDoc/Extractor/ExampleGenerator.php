<?php

namespace Bpa\ApiSandboxBundle\Service\ApiDoc\Extractor;

use Bpa\ApiSandboxBundle\Annotation\SandboxRequest;
use Bpa\ApiSandboxBundle\Annotation\SandboxResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Nelmio\ApiDocBundle\Extractor\HandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;

/**
 * Extracts example informations for NelmioApiDoc
 */
class ExampleGenerator implements HandlerInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param ApiDoc            $annotation
     * @param array             $annotations
     * @param Route             $route
     * @param \ReflectionMethod $method
     */
    public function handle(ApiDoc $annotation, array $annotations, Route $route, \ReflectionMethod $method)
    {
        if (null === $sandboxAnnotation = $this->findSandboxAnnotation($annotations)) {
            return;
        }

        if (null !== $example = $this->buildExamples($sandboxAnnotation, $route)) {
            $annotation->setDocumentation($annotation->getDocumentation().PHP_EOL.$example);
        }
    }

    /**
     * @param array $annotations
     *
     * @return SandboxRequest|null
     */
    private function findSandboxAnnotation(array $annotations)
    {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof SandboxRequest) {
                return $annotation;
            }
        }

        return null;
    }

    /**
     * @param SandboxRequest $annotation
     *
     * @return string
     */
    protected function buildExamples(SandboxRequest $annotation, Route $route)
    {
        $examples = '';

        // Iterate over all responses and generate markdown for this example
        foreach ($annotation->getResponses() as $response) {
            $examples .= $this->buildExample($route, $response);
        }

        return $examples;
    }

    /**
     * @param Route           $route
     * @param SandboxResponse $response
     *
     * @return mixed|string
     */
    protected function buildExample(Route $route, SandboxResponse $response)
    {
        $parameters = [];

        // Extract parameters for this response
        foreach ($response->getParameters() as $parameter) {
            $parameters[$parameter->getName()] = $parameter->getValue();
        }

        // Render this example
        $example = $this->twig->render('ApiSandboxBundle:doc:example.html.twig', [
            'response' => $response,
            'route' => $route,
            'parameters' => $parameters,
            'codes' => Response::$statusTexts,
        ]);

        return $example;
    }
}
