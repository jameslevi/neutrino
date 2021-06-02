<?php

namespace Graphite\Component\Neutrino\Exceptions;

use Exception;

class UnsupportedDriverException extends Exception
{
    /**
     * Override exception class.
     *
     * @param string $message
     * @param int $code
     * @return void
     */
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}