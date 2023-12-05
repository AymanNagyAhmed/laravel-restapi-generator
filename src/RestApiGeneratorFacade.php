<?php

namespace lararest\RestApiGenerator;

use Illuminate\Support\Facades\Facade;

/**
 * @see \lararest\RestApiGenerator\RestApiGenerator
 */
class RestApiGeneratorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'restapi-generator';
    }
}
