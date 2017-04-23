<?php

declare(strict_types=1);

namespace Nofw\Error\Tests;

use Nofw\Error\ErrorHandler;
use Nofw\Error\NullErrorHandler;
use PHPUnit\Framework\TestCase;

final class NullErrorHandlerTest extends TestCase
{
    /**
     * @var NullErrorHandler
     */
    private $errorHandler;

    public function setUp(): void
    {
        $this->errorHandler = new NullErrorHandler();
    }

    /**
     * @test
     */
    public function it_is_an_error_handler(): void
    {
        $this->assertInstanceOf(ErrorHandler::class, $this->errorHandler);
    }
}
