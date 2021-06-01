<?php

namespace Graphite\Component\Neutrino\Response;

use Graphite\Component\Neutrino\BaseResponse;

class ExecResponse extends BaseResponse
{
    /**
     * Return number of affected rows.
     *
     * @return int
     */
    public function affectedRows()
    {
        return $this->stmt->rowCount();
    }
}