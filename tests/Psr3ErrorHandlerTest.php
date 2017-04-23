<?php

declare(strict_types=1);

namespace Nofw\Error\Tests;

use Gamez\Psr\Log\TestLoggerTrait;
use Nofw\Error\Psr3ErrorHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

final class Psr3ErrorHandlerTest extends TestCase
{
    use TestLoggerTrait;
    /**
     * @test
     */
    public function it_logs_at_error_level_by_default(): void
    {
        $logger = $this->getTestLogger();

        $errorHandler = new Psr3ErrorHandler($logger);

        $e = new \Exception();

        $errorHandler->handle($e);

        $this->assertTrue($logger->hasRecord(LogLevel::ERROR));
    }

    /**
     * @test
     */
    public function it_accepts_a_log_level_map(): void
    {
        $logger = $this->getTestLogger();

        $levelMap = [
            \Exception::class => LogLevel::CRITICAL,
        ];

        $errorHandler = new Psr3ErrorHandler($logger, $levelMap);

        $e = new \Exception();

        $errorHandler->handle($e);

        $this->assertTrue($logger->hasRecord(LogLevel::CRITICAL));
    }

    /**
     * @test
     * @dataProvider errorProvider
     */
    public function it_detects_the_error_type(\Throwable $e, string $type): void
    {
        $logger = $this->getTestLogger();

        $errorHandler = new Psr3ErrorHandler($logger);

        $errorHandler->handle($e);

        $this->assertTrue($logger->hasRecord($type));
    }

    /**
     * @test
     * @dataProvider errorProvider
     */
    public function it_follows_the_log_template(\Throwable $e, string $type): void
    {
        $logger = $this->getTestLogger();

        $errorHandler = new Psr3ErrorHandler($logger);

        $errorHandler->handle($e);

        $this->assertTrue(
            $logger->hasRecord(
                sprintf(
                    '%s \'%s\' with message \'%s\' in %s(%s)',
                    $type,
                    get_class($e),
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                )
            )
        );
    }

    public function errorProvider()
    {
        return [
            [
                new class('Message') extends \Exception {
                    protected $file = 'file';
                    protected $line = 1;
                },
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
}
