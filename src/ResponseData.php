<?php

namespace Graphite\Component\Neutrino;

use Graphite\Component\Objectify\Objectify;

class ResponseData extends Objectify
{
    /**
     * Store response data index number.
     *
     * @var int
     */
    private $index;

    /**
     * Override parent constructor.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data, int $index)
    {
        parent::__construct($data, true);
        $this->index = $index;
    }

    /**
     * Return data index number.
     *
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }
}