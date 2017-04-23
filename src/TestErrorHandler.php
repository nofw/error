<?php

namespace Nofw\Error;

/**
 * This handler can be used in tests.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class TestErrorHandler implements ErrorHandler
{
    /**
     * @var \SplObjectStorage
     */
    private $errors;

    public function __construct()
    {
        $this->errors = new \SplObjectStorage();
    }

    public function handle(\Throwable $t, array $context = []): void
    {
        $this->errors->attach($t, $context);
    }

    /**
     * Checks whether an error has been handled.
     */
    public function contains(\Throwable $t): bool
    {
        return $this->errors->contains($t);
    }

    /**
     * Returns the context for an error or null if not found.
     */
    public function getContext(\Throwable $t): ?array
    {
        if ($this->errors->contains($t)) {
            return $this->errors[$t];
        }

        return null;
    }

    /**
     * Returns the errors in the form of [error, context].
     */
    public function getErrors(): array
    {
        $errors = [];

        foreach ($this->errors as $error) {
            $errors[] = [$error, $this->errors[$error]];
        }

        return $errors;
    }
}
