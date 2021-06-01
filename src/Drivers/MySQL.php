<?php

namespace Graphite\Component\Neutrino\Drivers;

use Graphite\Component\Neutrino\Driver;
use PDO;

class MySQL extends Driver
{
    /**
     * Set dsn string segments.
     *
     * @return void
     */
    public function dsn()
    {
        $port = $this->context->getPort();
        $charset = $this->context->getCharset();

        $this->context->addDsnSegment('host', $this->context->getServer())
                      ->addDsnSegment('dbname', $this->context->getDatabase());

        if(!is_null($port))
        {
            $this->context->addDsnSegment('port', $port);
        }

        if(!is_null($charset))
        {
            $this->context->addDsnSegment('charset', $charset);
        }
    }

    /**
     * Force queries to be buffered.
     *
     * @return $this
     */
    public function useBufferedQuery()
    {
        return $this->addOption(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    }

    /**
     * Set the size of the buffer that holds the result.
     *
     * @param int $size
     * @return $this
     */
    public function setMaxBufferSize(int $size)
    {
        return $this->addOption(PDO::MYSQL_ATTR_MAX_BUFFER_SIZE, $size);
    }

    /**
     * Specify if query execution is direct or prepared.
     *
     * @return $this
     */
    public function directQuery()
    {
        return $this->addOption(PDO::MYSQL_ATTR_DIRECT_QUERY, true);
    }
}