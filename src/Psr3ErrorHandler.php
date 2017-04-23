<?php

declare(strict_types=1);

namespace Nofw\Error;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * This handler can be used to log errors using a PSR-3 logger.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class Psr3ErrorHandler implements ErrorHandler
{
    /**
     * Defines which error levels should be mapped to certain error types by default.
     */
    private const DEFAULT_ERROR_LEVEL_MAP = [
        \ParseError::class => LogLevel::CRITICAL,
        \Throwable::class => LogLevel::ERROR,
    ];

    /**
     * The default log level.
     */
    private const DEFAULT_LOG_LEVEL = LogLevel::ERROR;

    /**
     * The key under which the error should be passed to the log context.
     */
    public const ERROR_KEY = 'error';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Defines which error levels should be mapped to certain error types.
     *
     * Note: The errors are checked in order, so if you want to define fallbacks for classes higher in the tree
     * make sure to add them to the end of the map.
     *
     * @var array
     */
    private $levelMap = [];

    /**
     * Ignores the severity when detecting the log level.
     *
     * @var bool
     */
    private $ignoreSeverity = false;

    /**
     * Enables the handler to accept detecting non-PSR-3 log levels.
     *
     * Note: when detecting an invalid level the handler will silently fall back to the default.
     *
     * @var bool
     */
    private $allowNonPsrLevels = false;

    public function __construct(LoggerInterface $logger, array $levelMap = [])
    {
        $this->logger = $logger;

        // Keep user maintained order
        $this->levelMap = array_replace($levelMap, self::DEFAULT_ERROR_LEVEL_MAP, $levelMap);
    }

    /**
     * Ignores the severity when detecting the log level.
     */
    public function ignoreSeverity(bool $ignoreSeverity = true): void
    {
        $this->ignoreSeverity = $ignoreSeverity;
    }

    /**
     * Enables the handler to accept detecting non-PSR-3 log levels.
     */
    public function allowNonPsrLevels(bool $allowNonPsrLevels = true): void
    {
        $this->allowNonPsrLevels = $allowNonPsrLevels;
    }

    public function handle(\Throwable $t, array $context = []): void
    {
        $context[self::ERROR_KEY] = $t;

        $this->logger->log(
            $this->getLevel($t, $context),
            sprintf(
                '%s \'%s\' with message \'%s\' in %s(%s)',
                $this->getType($t),
                get_class($t),
                $t->getMessage(),
                $t->getFile(),
                $t->getLine()
            ),
            $context
        );
    }

    /**
     * Determines the level for the error.
     */
    private function getLevel(\Throwable $t, array $context): string
    {
        // Check if the severity matches a PSR-3 log level
        if (
            false === $this->ignoreSeverity &&
            isset($context[Context::SEVERITY]) &&
            is_string($context[Context::SEVERITY]) &&
            $this->validateLevel($context[Context::SEVERITY])
        ) {
            return $context[Context::SEVERITY];
        }

        // Find the log level based on the error in the level map (avoid looping through the whole array)
        // Note: this ignores the order defined in the map.
        $class = get_class($t);
        if (isset($this->levelMap[$class]) && $this->validateLevel($this->levelMap[$class])) {
            return $this->levelMap[$class];
        }

        // Find the log level based on the error in the level map
        foreach ($this->levelMap as $className => $candidate) {
            if ($t instanceof $className && $this->validateLevel($candidate)) {
                return $candidate;
            }
        }

        // Return the default log level
        return self::DEFAULT_LOG_LEVEL;
    }

    /**
     * Checks whether a log level exists.
     */
    private function checkLevel(string $level): bool
    {
        return defined(sprintf('%s::%s', LogLevel::class, strtoupper($level)));
    }

    /**
     * Validates whether a log level exists (if non-PSR levels are not allowed).
     */
    private function validateLevel(string $level): bool
    {
        return $this->allowNonPsrLevels || $this->checkLevel($level);
    }

    /**
     * Determines the error type.
     */
    private function getType(\Throwable $t): string
    {
        if ($t instanceof \Exception) {
            return 'Exception';
        } elseif ($t instanceof \Error) {
            return 'Error';
        }

        return 'Throwable';
    }
}
