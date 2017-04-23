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
    public function it_logs_at_error_level_and_attaches_the_error_to_the_context_by_default()
    {
        /** @var LoggerInterface|ObjectProphecy $logger */
        $logger = $this->prophesize(LoggerInterface::class);

        $errorHandler = new Psr3ErrorHandler($logger->reveal());

        $e = new \Exception();

        $logger->log(LogLevel::ERROR, Argument::type('string'), [Psr3ErrorHandler::ERROR_KEY => $e])->shouldBeCalled();

        $errorHandler->handle($e);
    }

    /**
     * @test
     */
    public function it_accepts_a_log_level_map()
    {
        /** @var LoggerInterface|ObjectProphecy $logger */
        $logger = $this->prophesize(LoggerInterface::class);

        $levelMap = [
            \Exception::class => LogLevel::CRITICAL,
        ];

        $errorHandler = new Psr3ErrorHandler($logger->reveal(), $levelMap);

        $e = new \Exception();

        $logger->log(LogLevel::CRITICAL, Argument::type('string'), [Psr3ErrorHandler::ERROR_KEY => $e])->shouldBeCalled();

        $errorHandler->handle($e);
    }
}
