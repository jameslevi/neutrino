<?php

namespace Graphite\Component\Neutrino;

use PDOStatement;

abstract class BaseResponse
{
    /**
     * Store PDOStatement object.
     *
     * @var object
     */
    protected $stmt;

    /**
     * Store context object.
     *
     * @var object
     */
    protected $context;

    /**
     * Construct a new response object.
     *
     * @param PDOStatement $stmt
     * @param Neutrino $context
     * @return void
     */
    public function __construct(PDOStatement $stmt, Neutrino $context)
    {
        $this->stmt = $stmt;
        $this->context = $context;
    }
}