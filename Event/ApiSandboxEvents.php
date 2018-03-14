<?php

namespace Bpa\ApiSandboxBundle\Event;

/**
 * Class ApiSandboxEvents
 */
final class ApiSandboxEvents
{
    /**
     * Fires after the annotations were loaded
     */
    const ANNOTATIONS_LOADED = 'api.sandbox.annotations.loaded';

    /**
     * Fires when a matching response is found to further check the response in custom integrations
     */
    const RESPONSE_MATCH = 'api.sandbox.response.match';
}
