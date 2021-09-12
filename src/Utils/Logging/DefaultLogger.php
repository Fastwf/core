<?php

namespace Fastwf\Core\Utils\Logging;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class DefaultLogger implements LoggerInterface
{

    private const INDENTATION = "    "; 

    use LoggerTrait;

    private $fp;

    public function __construct($filename = 'php://stderr')
    {
        $this->fp = \fopen($filename, 'a');
    }

    /**
     * {@inheritDoc}
     */
    public function log($level, $message, array $context = array())
    {
        // Add suffix to allows to handle exception for error logs
        $suffix = PHP_EOL;
        if (array_key_exists('exception', $context) && $context['exception'] instanceof \Throwable)
        {
            // Add the stack trace string formatted with indentation
            $suffix .= self::INDENTATION
                . \str_replace("\n", "\n" . self::INDENTATION, $context['exception']->getTraceAsString())
                . PHP_EOL;
        }

        // Add date time to the log
        $date = (new \DateTime())->format("Y-m-d G:i:s.v");

        $fullMessage = "[${date}] ${level} - ${message}${suffix}";

        // Write and flush the log
        \fwrite($this->fp, $fullMessage);
        \fflush($this->fp);
    }
}
