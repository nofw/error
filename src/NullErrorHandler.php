<?php

namespace Nofw\Error;

/**
 * This handler can be used to avoid conditional calls.
 *
 * Error monitoring should always be optional, and if no handler is provided to your
 * library creating a NullHandler instance to have something to throw logs at
 * is a good way to avoid littering your code with `if ($this->errorHandler) { }`
 * blocks.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class NullErrorHandler implements ErrorHandler
{
    public function handle(\Throwable $t, array $context = []): void
    {
        // noop
    }
}
