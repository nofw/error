<?php

declare(strict_types=1);

namespace Nofw\Error\Tests;

use Nofw\Error\Context;
use Nofw\Error\ErrorHandler;
use Nofw\Error\TestErrorHandler;
use PHPUnit\Framework\TestCase;

final class TestErrorHandlerTest extends TestCase
{
    /**
     * @var TestErrorHandler
     */
    private $errorHandler;

    public function setUp(): void
    {
        $this->errorHandler = new TestErrorHandler();
    }

    /**
     * @test
     */
    public function it_is_an_error_handler(): void
    {
        $this->assertInstanceOf(ErrorHandler::class, $this->errorHandler);
    }

    /**
     * @test
     */
    public function it_handles_an_error(): void
    {
        $e = new \Exception();

        $this->errorHandler->handle($e, []);

        $this->assertEquals([[$e, []]], $this->errorHandler->getErrors());
    }

    /**
     * @test
     */
    public function it_contains_an_error(): void
    {
        $e = new \Exception();

        $this->errorHandler->handle($e, []);

        $this->assertTrue($this->errorHandler->contains($e));
    }

    /**
     * @test
     */
    public function it_contains_the_context_for_an_error(): void
    {
        $e = new \Exception();
        $context = [Context::SEVERITY => 'info'];

        $this->errorHandler->handle($e, $context);

        $this->assertEquals($context, $this->errorHandler->getContext($e));
    }

    /**
     * @test
     */
    public function it_returns_null_for_the_context_when_an_error_is_not_found(): void
    {
        $e = new \Exception();

        $this->assertNull($this->errorHandler->getContext($e));
    }
}
