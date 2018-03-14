# API Sandbox Bundle

This bundle is designed to prevent requests using your real controllers and responding with a fake
response you defined. It integrates nicely with `NelmioApiDocBundle` and `FOSRestBundle`.

When using `NelmioApiDocBundle` this bundle will generate `curl` examples for your provided sandbox
requests automatically which will show in the documentation.

## Requirements

ApiSandboxBundle required php >= 5.5 and symfony >= 2.7.

## Installation

The easiest way to install this library is through [composer](http://getcomposer.org/). 
Just add the following lines to your **composer.json** file and run `composer.phar update`:

```json
{
   "require": {
        "bpa/api-sandbox-bundle": "~0.1"
    }
}
```

## Configuration

Load the bundle in your `AppKernel.php`:

```php
class AppKernel extends Kernel {
    public function registerBundles() {
        // ...
        $bundles = [
            // ... 
            new Bpa\ApiSandboxBundle\ApiSandboxBundle(),
            // ...
        ];
        // ...
    }
}
```

I would recommend to create a new environment for your sandbox by copying the `app.php` front controller
to something like `app_sandbox.php`. In your new front controller you have to change the following line
to the new environment:

```php
$kernel = new AppKernel('sandbox', false);
```

Create a new `config_sandbox.yml` in your `app/config` directory with the following contents:

```yaml
imports:
    - { resource: config_prod.yml }

api_sandbox:
    enabled: true
    force_response: true
```

This takes all settings from your `prod` environment and enables the sandbox for your new `sandbox` 
environment.

## Usage

### A basic Controller

When using the `FOSRestBundle` and `NelmioApiDocBundle` for your API an integration within your
application could look something like this:

```php
<?php

namespace AppBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Bpa\ApiSandboxBundle\Annotation as Bpa;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for books
 */
class BookController extends FOSRestController
{
    /**
     * Get book informations
     *
     * Retrieve informations about a specific book
     *
     * @ApiDoc(
     *     section="Books",
     *     resource=true,
     *     statusCodes={
     *         200="Ok",
     *     }
     * )
     *
     * @Bpa\SandboxRequest(
     *     responses={
     *         @Bpa\SandboxResponse(
     *             statusCode=200,
     *             content="@AppBundle/Resources/sandbox/responses/books/get.json",
     *         )
     *     }
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAction(Request $request)
    {
        return new JsonResponse([
            'books' => [ 'id' => 1, 'title' => 'A Brief History of Time' ],
        ]);        
    }
}
```

### Using parameters

It's possible to define multiple responses for a single Controller action and distinguish between 
them by using the `SandboxRequest\Parameter` Annotation like this:

```php
<?php

class BookController extends FOSRestController
{
    /**
     * ...
     * 
     * @Bpa\SandboxRequest(
     *    responses={
     *        @Bpa\SandboxResponse(
     *            statusCode=200,
     *            content="@AppBundle/Resources/sandbox/responses/books/get_1.json",
     *            parameters={
     *                @Bpa\SandboxRequest\Parameter(name="id", value="1"),
     *            }
     *        ),
     *        @Bpa\SandboxResponse(
     *            statusCode=200,
     *            content="@AppBundle/Resources/sandbox/responses/books/get_2.json",
     *            parameters={
     *                @Bpa\SandboxRequest\Parameter(name="id", value="2"),
     *            }
     *        ),
     *        @Bpa\SandboxResponse(
     *            statusCode=200,
     *            content="@AppBundle/Resources/sandbox/responses/books/get.json",
     *        )
     *    }
     * )
     *
     * ...
     */
    public function getAction(Request $request) { /* ... */ }
}
```

If you now provide the parameter `id = 1` the first response will be returned. With `id = 2` the second
one is returned and for all other requests the third response will be returned.

## Automatic generation of documentation

With all this set up you will see automatically generated examples API documentation with
the `NelmioApiDocBundle`. If you designed your own theme for your documentation you are able 
to provide your own `ExampleGenerator` which only should extend the provided `ExampleGenerator` 
and override the `buildExample` method:

```php
<?php

namespace DocBundle\Service\ApiDoc\Extractor;

use Bpa\ApiSandboxBundle\Annotation\SandboxResponse;
use Bpa\ApiSandboxBundle\Service\ApiDoc\Extractor\ExampleGenerator as BaseGenerator;
use Symfony\Component\Routing\Route;

class ExampleGenerator extends BaseGenerator
{
    /**
     * @param Route           $route
     * @param SandboxResponse $response
     *
     * @return mixed|string
     */
    protected function buildExample(Route $route, SandboxResponse $response)
    {
        return 'Your custom example markdown';
    }
}
```

## Contributing

Please feel free to contribute to this bundle. Any contribution is highly appreciated and
will be reviewed. 
