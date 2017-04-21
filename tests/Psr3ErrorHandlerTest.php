<?php

namespace Nofw\Error\Tests;

use Nofw\Error\Psr3ErrorHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class Psr3ErrorHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_logs_at_critical_level_and_attaches_the_error_to_the_context_by_default()
    {
        /** @var LoggerInterface|ObjectProphecy $logger */
        $logger = $this->prophesize(LoggerInterface::class);

        $errorHandler = new Psr3ErrorHandler();
        $errorHandler->setLogger($logger->reveal());

        $exception = new \Exception();

        $logger->log(LogLevel::CRITICAL, Argument::type('string'), ['throwable' => $exception])->shouldBeCalled();

        $errorHandler->handle($exception);
    }

    /**
     * @test
     */
    public function it_accepts_a_log_level()
    {
        /** @var LoggerInterface|ObjectProphecy $logger */
        $logger = $this->prophesize(LoggerInterface::class);

        $errorHandler = new Psr3ErrorHandler(LogLevel::ERROR);
        $errorHandler->setLogger($logger->reveal());

        $exception = new \Exception();

        $logger->log(LogLevel::ERROR, Argument::type('string'), ['throwable' => $exception])->shouldBeCalled();

        $errorHandler->handle($exception);
    }

    /**
     * @test
     */
    public function it_allows_to_disable_attaching_the_error()
    {
        /** @var LoggerInterface|ObjectProphecy $logger */
        $logger = $this->prophesize(LoggerInterface::class);

        $errorHandler = new Psr3ErrorHandler(LogLevel::CRITICAL, false);
        $errorHandler->setLogger($logger->reveal());

        $exception = new \Exception();

        $logger->log(LogLevel::CRITICAL, Argument::type('string'), [])->shouldBeCalled();

        $errorHandler->handle($exception);
    }
}
