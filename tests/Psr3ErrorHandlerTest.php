<?php

declare(strict_types=1);

namespace Nofw\Error\Tests;

use Gamez\Psr\Log\TestLogger;
use Gamez\Psr\Log\TestLoggerTrait;
use Nofw\Error\Context;
use Nofw\Error\Psr3ErrorHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

final class Psr3ErrorHandlerTest extends TestCase
{
    use TestLoggerTrait;

    /**
     * @var TestLogger
     */
    private $logger;

    /**
     * @var Psr3ErrorHandler
     */
    private $errorHandler;

    public function setUp(): void
    {
        $this->logger = $this->getTestLogger();
        $this->errorHandler = new Psr3ErrorHandler($this->logger);
    }

    /**
     * @test
     */
    public function it_logs_at_error_level_by_default(): void
    {
        $this->errorHandler->handle($this->getException());

        $this->assertLoggerHasRecord(LogLevel::ERROR);
    }

    /**
     * @test
     */
    public function it_accepts_a_log_level_map(): void
    {
        $levelMap = [
            \Exception::class => LogLevel::CRITICAL,
        ];

        $this->errorHandler = new Psr3ErrorHandler($this->logger, $levelMap);

        $this->errorHandler->handle($this->getException());

        $this->assertLoggerHasRecord(LogLevel::CRITICAL);
    }

    /**
     * @test
     */
    public function it_ignores_the_log_level_map_when_the_level_is_not_valid(): void
    {
        $levelMap = [
            \Throwable::class => 'invalid',
        ];

        $this->errorHandler = new Psr3ErrorHandler($this->logger, $levelMap);

        $this->errorHandler->handle($this->getException());

        $this->assertLoggerHasRecord(LogLevel::ERROR);
    }

    /**
     * @test
     */
    public function it_does_not_ignore_the_log_level_map_when_non_psr_levels_are_allowed(): void
    {
        $this->logger->allowNonPsrLevels();

        $levelMap = [
            \Throwable::class => 'invalid',
        ];

        $this->errorHandler = new Psr3ErrorHandler($this->logger, $levelMap);
        $this->errorHandler->allowNonPsrLevels();

        $this->errorHandler->handle($this->getException());

        $this->assertLoggerHasRecord('invalid');
    }

    /**
     * @test
     */
    public function it_ignores_the_log_level_map_order_when_there_is_an_exact_match(): void
    {
        $e = $this->getException();

        $levelMap = [
            \Throwable::class => LogLevel::ERROR,
            get_class($e) => LogLevel::CRITICAL,
        ];

        $this->errorHandler = new Psr3ErrorHandler($this->logger, $levelMap);

        $this->errorHandler->handle($e);

        $this->assertLoggerHasRecord(LogLevel::CRITICAL);
    }

    /**
     * @test
     */
    public function it_ignores_the_exact_match_when_the_level_is_not_valid(): void
    {
        $levelMap = [
            \Exception::class => 'invalid',
        ];

        $this->errorHandler = new Psr3ErrorHandler($this->logger, $levelMap);

        $this->errorHandler->handle($this->getException());

        $this->assertLoggerHasRecord(LogLevel::ERROR);
    }

    /**
     * @test
     */
    public function it_does_not_ignore_the_exact_match_when_non_psr_levels_are_allowed(): void
    {
        $this->logger->allowNonPsrLevels();

        $levelMap = [
            \Exception::class => 'invalid',
        ];

        $this->errorHandler = new Psr3ErrorHandler($this->logger, $levelMap);
        $this->errorHandler->allowNonPsrLevels();

        $this->errorHandler->handle($this->getException());

        $this->assertLoggerHasRecord('invalid');
    }

    /**
     * @test
     */
    public function it_detects_the_log_level_based_on_severity(): void
    {
        $this->errorHandler->handle(
            $this->getException(),
            [
                Context::SEVERITY => LogLevel::WARNING,
            ]
        );

        $this->assertLoggerHasRecord(LogLevel::WARNING);
    }

    /**
     * @test
     */
    public function it_ignores_the_severity_when_the_level_is_not_valid(): void
    {
        $this->errorHandler->handle(
            $this->getException(),
            [
                Context::SEVERITY => 'invalid',
            ]
        );

        $this->assertLoggerHasRecord(LogLevel::ERROR);
    }

    /**
     * @test
     */
    public function it_does_not_ignore_the_severity_when_non_psr_levels_are_allowed(): void
    {
        $this->logger->allowNonPsrLevels();
        $this->errorHandler->allowNonPsrLevels();

        $this->errorHandler->handle(
            $this->getException(),
            [
                Context::SEVERITY => 'invalid',
            ]
        );

        $this->assertLoggerHasRecord('invalid');
    }

    /**
     * @test
     */
    public function it_ignores_the_severity(): void
    {
        $this->errorHandler->ignoreSeverity();

        $this->errorHandler->handle(
            $this->getException(),
            [
                Context::SEVERITY => LogLevel::WARNING,
            ]
        );

        $this->assertLoggerHasRecord(LogLevel::ERROR);
    }

    /**
     * @test
     * @dataProvider errorProvider
     */
    public function it_detects_the_error_type(\Throwable $e, string $type): void
    {
        $this->errorHandler->handle($e);

        $this->assertLoggerHasRecord($type);
    }

    /**
     * @test
     * @dataProvider errorProvider
     */
    public function it_follows_the_log_template(\Throwable $e, string $type): void
    {
        $this->errorHandler->handle($e);

        $this->assertLoggerHasRecord(
            sprintf(
                '%s \'%s\' with message \'%s\' in %s(%s)',
                $type,
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            )
        );
    }

    public function errorProvider(): array
    {
        return [
            [
                $this->getException(),
                'Exception',
            ],
            [
                new class('Message') extends \Error {
                    protected $file = 'file';
                    protected $line = 1;
                },
                'Error',
            ],
        ];
    }

    /**
     * Creates an exception with clean values.
     *
     * Note: this is necessary because this file has the string 'Error' in its name which leads to false positive checks.
     */
    private function getException(): \Exception
    {
        return new class('Message') extends \Exception {
            protected $file = 'file';
            protected $line = 1;
        };
    }

    private function assertLoggerHasRecord(string $record): void
    {
        $this->assertTrue($this->logger->hasRecord($record));
    }
}
