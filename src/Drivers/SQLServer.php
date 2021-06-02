<?php

namespace Graphite\Component\Neutrino\Drivers;

use Graphite\Component\Neutrino\Driver;
use PDO;

class SQLServer extends Driver
{
    /**
     * Set dsn string segments.
     *
     * @return void
     */
    public function dsn()
    {
        $port = $this->context->getPort();

        $this->context->addDsnSegment('server', $this->context->getServer() . (!is_null($port) ? (',' . $port) : ''))
                      ->addDsnSegment('database', $this->context->getDatabase());
    }

    /**
     * Set the size of the buffer that holds the result.
     *
     * @param int $size
     * @return $this
     */
    public function setMaxBufferSize(int $size)
    {
        return $this->addOption(PDO::SQLSRV_ATTR_CLIENT_BUFFER_MAX_KB_SIZE, $size);
    }

    /**
     * Set the query timeout in seconds.
     *
     * @param int $timeout
     * @return $this
     */
    public function setTimeout(int $seconds)
    {
        return $this->addOption(PDO::SQLSRV_ATTR_QUERY_TIMEOUT, $seconds);
    }
}