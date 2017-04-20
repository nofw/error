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
     * Application context key.
     *
     * This is an array value holding information about application and it's environment.
     *
     * Some example keys:
     *  - os
     *  - hostname
     *  - language (eg. PHP 7.1)
     *  - version
     *  - environment (eg. staging, production)
     *  - url
     *  - rootDirectory
     *  - component
     *  - action
     */
    public const APP = 'app';

    /**
     * User context key.
     *
     * This is an array value holding information about the current user (such as ID, username or email).
     */
    public const USER = 'user';

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
     * Environment context key.
     *
     * This is an array value holding the environment variables.
     */
    public const ENVIRONMENT = 'environment';

    /**
     * Parameters context key.
     *
     * This is an array value holding additional parameters which might help resolving the problem.
     */
    public const PARAMETERS = 'parameters';
}
