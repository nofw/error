<?php

namespace Nofw\Error;

/**
 * Common context option keys.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Context
{
    /**
     * Severity context key of the error.
     *
     * This is a string value which MAY match with the PSR-3 log levels.
     */
    public const SEVERITY = 'severity';

    /**
     * Request context key.
     *
     * This is an array value holding information about the current request.
     * (eg. ip, headers, URL, HTTP method, and HTTP params)
     */
    public const REQUEST = 'request';

    /**
     * Session context key.
     *
     * This is an array value holding information about the current session.
     */
    public const SESSION = 'session';

    /**
     * Parameters context key.
     *
     * This is an array value holding additional parameters which might help resolving the problem.
     */
    public const PARAMETERS = 'parameters';
}
