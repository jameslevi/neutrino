<?php

namespace Graphite\Component\Neutrino;

use Error;
use Exception;
use PDO;
use PDOException;

class Neutrino extends DriverSupport
{
    /**
     * Store PDO connection object.
     *
     * @var PDO
     */
    private $conn;

    /**
     * Database driver to use.
     *
     * @var string
     */
    protected $driver;

    /**
     * Name of the database.
     *
     * @var string
     */
    private $database;

    /**
     * Connection username.
     *
     * @var string
     */
    private $username;

    /**
     * Connection password.
     *
     * @var string
     */
    private $password;

    /**
     * Store server name.
     *
     * @var string
     */
    private $server = 'localhost';

    /**
     * Server port number.
     *
     * @var int
     */
    private $port;

    /**
     * Character set to use. 
     *
     * @var string
     */
    private $charset;

    /**
     * Store list of PDO options.
     *
     * @var array
     */
    private $options = array();

    /**
     * Store data source name string.
     *
     * @var string
     */
    private $dsn;

    /**
     * Store dsn string segments.
     *
     * @var array
     */
    private $dsn_segments = array();

    /**
     * Determine if connection is established.
     *
     * @var boolean
     */
    private $connected = false;

    /**
     * Store connection error message.
     *
     * @var string
     */
    private $error;

    /**
     * Store driver context object.
     *
     * @var object
     */
    private $context;

    /**
     * Construct a new database connection instance.
     *
     * @param string $driver
     * @return void
     */
    public function __construct(string $driver)
    {
        $this->driver = $driver;

        $this->initDriver();
        $this->setErrorMode('exception');
    }

    /**
     * Instantiate driver methods.
     *
     * @return void
     */
    private function initDriver()
    {
        try
        {
            if(!static::supported($this->driver))
            {
                throw new Error('Access to unsupported driver.');
            }
            
            $this->context = new static::$drivers[$this->driver]($this);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

    /**
     * Call driver methods from driver object.
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        try
        {
            if(!method_exists($this->context, $name))
            {
                $driver = $this->driver;
                throw new Error("Unknown $driver driver method called.");        
            }

            $this->context->{$name}(...$arguments);
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }

        return $this;
    }

    /**
     * Return database driver to use.
     *
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set database name.
     *
     * @param string $database
     * @return $this
     */
    public function setDatabase(string $database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * Return the name of the database.
     *
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Set connection username.
     *
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Return connection username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set connection password.
     *
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Return connection password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set server name to use.
     *
     * @param string $server
     * @return $this
     */
    public function setServer(string $server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Return the server name.
     *
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set server port to use.
     *
     * @param int $port
     * @return $this
     */
    public function setPort(int $port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Return server port number.
     *
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set character set encoding.
     *
     * @param string $charset
     * @return void
     */
    public function setCharset(string $charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * Return character set enconding.
     *
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Set data source name string.
     *
     * @param string $dsn
     * @return $this
     */
    public function setDsn(string $dsn)
    {
        $this->dsn = $dsn;

        return $this;
    }

    /**
     * Return data source name string.
     *
     * @return string
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * Add new PDO option.
     *
     * @param int $option
     * @param mixed $value
     * @return $this
     */
    public function addOption(int $option, $value)
    {
        $this->options[$option] = $value;
        
        return $this;
    }

    /**
     * Determine if connection is established.
     *
     * @return boolean
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * Add new data source name segment.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addDsnSegment(string $name, string $value)
    {
        $this->dsn_segments[] = $name . '=' . $value;

        return $this;
    }

    /**
     * Start database connection.
     *
     * @return bool
     */
    public function connect()
    {
        if(is_null($this->conn))
        {
            if(is_null($this->dsn))
            {
                $this->context->dsn();
                $this->setDsn($this->driver . ':' . implode(';', $this->dsn_segments));
            }

            try
            {
                $pdo = new PDO($this->getDsn(), $this->username, $this->password);
                
                foreach($this->options as $key => $option)
                {
                    $pdo->setAttribute($key, $option);
                }

                $this->connected = true;
                $this->conn = $pdo;
            }
            catch(PDOException $e)
            {
                $this->connected = false;
                $this->error = $e->getMessage();
            }
        }
    
        return $this->connected;
    }

    /**
     * Execute an SQL query.
     *
     * @param string $query
     * @return object
     */
    public function query(string $query)
    {
        return new Query($this, $this->conn->prepare(str_replace('  ', ' ', trim($query)), array(
            PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        )));
    }

    /**
     * Return connection error message.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error;
    }

    /**
     * Return the current PDO object.
     *
     * @return PDO
     */
    public function getPDOObject()
    {
        return $this->conn;
    }

    /**
     * Close the current database connection.
     *
     * @return $this
     */
    public function close()
    {
        $this->conn = null;
        $this->connected = false;
        
        return $this;
    }

    /**
     * Factory for creating new instance.
     *
     * @param string $name
     * @param array $arguments
     * @return $this
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if(!static::supported($name))
        {
            throw new Error('Access to unsupported driver.');
        }
        
        return (new self($name))->setDatabase($arguments[0]);
    }

    /**
     * Return the current version.
     *
     * @return string
     */
    public static function version()
    {
        return 'Neutrino version 1.0.0';
    }
}