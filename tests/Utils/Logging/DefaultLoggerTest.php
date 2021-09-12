<?php

namespace Fastwf\Tests\Utils\Logging;

use Psr\Log\LogLevel;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Utils\Logging\DefaultLogger;

class DefaultLoggerTest extends TestCase
{
    
    const OUT_FILENAME = __DIR__ . '/out.log';

    /**
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     */
    public function testSimpleMessage()
    {
        $logger = new DefaultLogger(self::OUT_FILENAME);

        $logger->log(LogLevel::INFO, "hello world");

        $this->assertNotSame(
            false,
            \strpos(
                \file_get_contents(self::OUT_FILENAME),
                LogLevel::INFO . " - hello world" . PHP_EOL
            )
        );
    }

    /**
     * @covers Fastwf\Core\Utils\Logging\DefaultLogger
     */
    public function testExceptionMessage()
    {
        $exception = new \Exception("runtime error");

        $logger = new DefaultLogger(self::OUT_FILENAME);

        $logger->log(LogLevel::CRITICAL, $exception->getMessage(), ['exception' => $exception]);

        $this->assertNotSame(
            false,
            \strpos(
                \file_get_contents(self::OUT_FILENAME),
                PHP_EOL . "    "
            )
        );
    }

    protected function tearDown(): void
    {
        if (\file_exists(self::OUT_FILENAME))
        {
            \unlink(self::OUT_FILENAME);
        }
    }

}