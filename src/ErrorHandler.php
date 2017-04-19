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
     * Severity context key of the error.
     *
     * This is a string value which MAY match with the PSR-3 log levels.
     */
    const SEVERITY = 'severity';

    /**
     * Request context key.
     *
     * This is an array value holding information about the current request.
     * (eg. ip, headers, URL, HTTP method, and HTTP params)
     */
    const REQUEST = 'request';

    /**
     * Session context key.
     *
     * This is an array value holding information about the current session.
     */
    const SESSION = 'session';

    /**
     * Parameters context key.
     *
     * This is an array value holding additional parameters which might help resolving the problem.
     */
    const PARAMETERS = 'parameters';

    /**
     * Handles an error and optionally provides additional context.
     *
     * Every error monitoring solution has it's own way to provide context about the error.
     * The above parameters follow common sense and it SHOULD be possible to fit them into any workflow.
     *
     * @param \Throwable $t
     * @param array      $context
     *
     * @return void
     */
    public function handle(\Throwable $t, array $context = []): void;
}
