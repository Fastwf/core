<?php

namespace Fastwf\Core\Components;


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
     * @param \Throwable $exception the exception raised during request process.
     * @param Fastwf\Core\Http\Frame\HttpRequest $request the incomming request is request preparation succed
     * @param Fastwf\Core\Http\Frame\HttpStreamResponse|null $response the prepared response if request handler was called
     * @return Fastwf\Core\Http\Frame\HttpStreamResponse|null the created response when exception is handled 
     *                                                        or null to propagate the exception.
     */
    public function catch($exception, $request, $response);

}
