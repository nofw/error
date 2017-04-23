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

    public function __construct(LoggerInterface $logger, array $levelMap = [])
    {
        $this->logger = $logger;

        // Keep user maintained order
        $this->levelMap = array_replace($levelMap, self::DEFAULT_ERROR_LEVEL_MAP, $levelMap);
    }

    public function handle(\Throwable $t, array $context = []): void
    {
        $context[self::ERROR_KEY] = $t;

        $this->logger->log(
            $this->getLevel($t),
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
     * Determine the level for the error.
     */
    private function getLevel(\Throwable $t): string
    {
        $level = LogLevel::ERROR;
        foreach ($this->levelMap as $className => $candidate) {
            if ($t instanceof $className) {
                $level = $candidate;
                break;
            }
        }

        return $level;
    }

    /**
     * Determine the error type.
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
