<?php

namespace Fastwf\Core\Components;

use Fastwf\Core\Http\Frame\HttpRequest;
use Fastwf\Core\Http\Frame\HttpStreamResponse;


/**
 * Exception handler interface.
 * 
 * Interface that allows to process exceptions.
 */
interface ExceptionHandler
{

    /**
     * Method called when an exception is thrown during request process.
     *
     * @param Throwable $exception the exception raised during request process.
     * @param HttpRequest $request the incomming request is request preparation succed.
     * @param HttpStreamResponse|null $response the prepared response if request handler was called.
     * @return HttpStreamResponse|null the created response when exception is handled or null to propagate the exception.
     */
    public function catch($exception, $request, $response);

}
