<?php

namespace Graphite\Component\Neutrino;

use PDO;
use PDOStatement;

class Query
{
    /**
     * Store query parameters.
     *
     * @var array
     */
    private $params = array();

    /**
     * Store context object.
     *
     * @var object
     */
    private $context;

    /**
     * Store PDO statement object.
     *
     * @var object
     */
    private $stmt;

    /**
     * Determine if query is successfull.
     *
     * @var bool
     */
    private $success = false;

    /**
     * SQL query to execute.
     *
     * @var string
     */
    private $sql;

    /**
     * Construct a new query instance.
     *
     * @param string $sql
     * @param Neutrino $context
     * @param PDOStatement $stmt
     * @return void
     */
    public function __construct(string $sql, Neutrino $context, PDOStatement $stmt)
    {
        $this->sql = $sql;
        $this->context = $context;
        $this->stmt = $stmt;
    }

    /**
     * Return SQL query to be execute.
     *
     * @return string
     */
    public function sql()
    {
        return $this->sql;
    }

    /**
     * Add new binded value
     *
     * @param string $key
     * @param mixed $value
     * @param int $type
     * @return void
     */
    public function addParam(string $key, $value, int $type = PDO::PARAM_STR)
    {
        $this->params[$key] = array('value' => $value, 'type' => $type);

        return $this;
    }

    /**
     * Add new string paramater.
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function addStringParam(string $key, string $value)
    {
        return $this->addParam($key, $value, PDO::PARAM_STR);
    }

    /**
     * Add new integer parameter.
     *
     * @param string $key
     * @param int $value
     * @return $this
     */
    public function addIntegerParam(string $key, int $value)
    {
        return $this->addParam($key, $value, PDO::PARAM_INT);
    }

    /**
     * Add new boolean parameter.
     *
     * @param string $key
     * @param boolean $value
     * @return $this
     */
    public function addBooleanParam(string $key, bool $value)
    {
        return $this->addParam($key, $value, PDO::PARAM_BOOL);
    }

    /**
     * Add new null parameter.
     *
     * @param string $key
     * @return $this
     */
    public function addNullParam(string $key)
    {
        return $this->addParam($key, null, PDO::PARAM_NULL);
    }

    /**
     * Execute the query.
     *
     * @return $this
     */
    private function execute()
    {
        foreach($this->params as $key => $param)
        {
            $this->stmt->bindValue(':' . $key, $param['value'], $param['type']);
        }

        if($this->stmt->execute())
        {
            $this->success = true;
        }

        return $this;
    }

    /**
     * Execute get query and expect results.
     *
     * @return Response
     */
    public function get()
    {
        return new Response(
                        \Graphite\Component\Neutrino\Response\GetResponse::class, 
                        $this->context, 
                        $this->execute()
                    );
    }

    /**
     * Execute query that has no result.
     *
     * @return Response
     */
    public function exec()
    {
        return new Response(
                        \Graphite\Component\Neutrino\Response\ExecResponse::class, 
                        $this->context, 
                        $this->execute()
                    );
    }

    /**
     * Determine if query has been executed successfully.
     *
     * @return bool
     */
    public function success()
    {
        return $this->success;
    }

    /**
     * Return the PDOStatement object.
     *
     * @return object
     */
    public function getPDOStatementObject()
    {
        return $this->stmt;
    }

    /**
     * Dump an SQL prepared command.
     *
     * @return mixed
     */
    public function dumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}