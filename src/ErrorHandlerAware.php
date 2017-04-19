<?php

namespace Nofw\Error;

/**
 * Describes an error handler aware instance.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface ErrorHandlerAware
{
    /**
     * Sets an error handler instance.
     *
     * @param ErrorHandler $errorHandler
     *
     * @return void
     */
    public function setErrorHandler(ErrorHandler $errorHandler): void;
}
