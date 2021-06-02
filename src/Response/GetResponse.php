<?php

namespace Graphite\Component\Neutrino\Response;

use Graphite\Component\Neutrino\BaseResponse;
use Graphite\Component\Neutrino\Exceptions\InvalidRowIndexException;
use Graphite\Component\Neutrino\ResponseData;
use PDO;

class GetResponse extends BaseResponse
{
    /**
     * Store fetched query result.
     *
     * @var array
     */
    private $result;

    /**
     * Fetch all query results.
     *
     * @return $this
     */
    public function fetch()
    {
        if(is_null($this->result))
        {
            $data = array();
            $index = 0;

            foreach($this->stmt->fetchAll(PDO::FETCH_ASSOC) as $result)
            {
                $data[] = new ResponseData($result, $index);
                $index++;
            }

            $this->stmt->closeCursor();
            $this->result = $data;
        }

        return $this;
    }

    /**
     * Return all results from the query.
     *
     * @return array
     */
    public function all()
    {
        return $this->result;
    }

    /**
     * Dynamically get key value if result has only 1 row.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        if($this->numRows() > 1)
        {
            throw new InvalidRowIndexException('Invalid row index.');
        }
            
        return $this->first()->{$name};
    }

    /**
     * Return list of column names from result.
     *
     * @return array
     */
    public function columnNames()
    {
        return $this->first()->keys();
    }

    /**
     * Return the list of values from an specific column.
     *
     * @param string $name
     * @return array
     */
    public function pluck(string $name)
    {
        $data = array();

        foreach($this->fetch()->all() as $item)
        {
            $data[] = $item->{$name};
        }

        return $data;
    }

    /**
     * Return row by index.
     *
     * @param int $n
     * @return object
     */
    public function get(int $n)
    {
        return $this->fetch()->all()[$n];
    }

    /**
     * Return the first row of the result.
     *
     * @return object
     */
    public function first()
    {
        return $this->get(0);
    }

    /**
     * Return the last row of the result.
     *
     * @return object
     */
    public function last()
    {
        return $this->get($this->numRows() - 1);
    }

    /**
     * Count the number of rows from result.
     *
     * @return int
     */
    public function numRows()
    {
        return count($this->fetch()->all());
    }

    /**
     * Determine if result is empty.
     *
     * @return bool
     */
    public function empty()
    {
        return $this->numRows() == 0;
    }

    /**
     * Return result as array.
     *
     * @return array
     */
    public function toArray()
    {
        $data = array();

        foreach($this->fetch()->all() as $item)
        {
            $data[] = $item->toArray();
        }

        return $data;
    }

    /**
     * Convert result array into json.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}