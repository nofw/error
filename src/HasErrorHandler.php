<?php

namespace Nofw\Error;

/**
 * Describes an error handler aware instance.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait HasErrorHandler
{
    /**
     * @var ErrorHandler
     */
    protected $errorHandler;

    /**
     * Sets an error handler instance.
     */
    public function setErrorHandler(ErrorHandler $errorHandler): void
    {
        $this->errorHandler = $errorHandler;
    }
}
