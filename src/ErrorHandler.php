<?php

namespace Nofw\Error;

/**
 * This interface describes an error handler which is responsible to report errors to the appropriate parties.
 * Be it an error monitoring or just a logging service.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface ErrorHandler
{
    /**
     * Handles an error and optionally provides additional context.
     *
     * Every error monitoring solution has it's own way to provide context about the error.
     * The above parameters follow common sense and it SHOULD be possible to fit them into any workflow.
     *
     * @see Context
     *
     * @param \Throwable $t
     * @param array      $context
     */
    public function handle(\Throwable $t, array $context = []): void;
}
