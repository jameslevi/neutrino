<?php

namespace Graphite\Component\Neutrino;

use Graphite\Component\Neutrino\Exceptions\UnknownMethodException;

class Response
{
    /**
     * Store context object.
     *
     * @var object
     */
    private $context;

    /**
     * Store PDOStatement object.
     *
     * @var object
     */
    private $stmt;

    /**
     * Store response object.
     *
     * @var object
     */
    private $response;

    /**
     * Determine if query is successfull.
     *
     * @var bool
     */
    private $success = false;

    /**
     * Undocumented function
     *
     * @param string $source
     * @param Neutrino $context
     * @param Query $query
     * @return void
     */
    public function __construct(string $source, Neutrino $context, Query $query)
    {
        $this->context = $context;
        $this->success = $query->success();
        $this->stmt = $query->getPDOStatementObject();
        $this->initResponse($source);
    }

    /**
     * Instantiate response object.
     *
     * @param string $source
     * @return void
     */
    private function initResponse(string $source)
    {
        $this->response = new $source($this->stmt, $this->context);
    }

    /**
     * Determine if query is successfull.
     *
     * @return bool
     */
    public function success()
    {
        return $this->success;
    }

    /**
     * Dynamically call response method.
     *
     * @param string $name
     * @param array $arguments
     * @return $this
     */
    public function __call(string $name, array $arguments)
    {
        if(!method_exists($this->response, $name))
        {
            throw new UnknownMethodException('Unknown response method.');
        }

        return $this->response->{$name}(...$arguments);
    }

    /**
     * Dynamically get properties from response object.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->response->{$name};
    }
}