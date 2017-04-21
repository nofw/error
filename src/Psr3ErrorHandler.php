<?php

declare(strict_types=1);

namespace Nofw\Error;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

/**
 * This handler can be used to log errors using a PSR-3 logger.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class Psr3ErrorHandler implements ErrorHandler, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * The log level to be used.
     *
     * @var string
     */
    private $level;

    /**
     * Attach the error to the context or not.
     *
     * @var bool
     */
    private $attachError;

    public function __construct(string $level = LogLevel::CRITICAL, bool $attachError = true)
    {
        $this->level = $level;
        $this->attachError = $attachError;
        $this->logger = new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function handle(\Throwable $t, array $context = []): void
    {
        if ($this->attachError) {
            $context['throwable'] = $t;
        }

        // Determine the error type
        if ($t instanceof \Exception) {
            $type = 'Exception';
        } elseif ($t instanceof \Error) {
            $type = 'Error';
        } else {
            $type = 'Throwable';
        }

        $this->logger->log(
            $this->level,
            sprintf(
                '%s \'%s\' with message \'%s\' in %s(%s)',
                $type,
                get_class($t),
                $t->getMessage(),
                $t->getFile(),
                $t->getLine()
            ),
            $context
        );
    }
}
