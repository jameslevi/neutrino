<?php

namespace Graphite\Component\Neutrino;

use PDO;

abstract class Driver
{
    /**
     * Store neutrino instance object.
     *
     * @var object
     */
    protected $context;

    /**
     * Construct a new driver instance.
     *
     * @param Neutrino $context
     * @return void
     */
    public function __construct(Neutrino $context)
    {
        $this->context = $context;
    }

    /**
     * Add new PDO option.
     *
     * @param int $option
     * @param mixed $value
     * @return $this
     */
    protected function addOption(int $option, $value)
    {
        $this->context->addOption($option, $value);

        return $this;
    }

    /**
     * Specifies how the driver will report failures.
     *
     * @param string $mode
     * @return $this
     */
    public function setErrorMode(string $mode)
    {
        $mode = strtolower($mode);

        if($mode == 'silent')
        {
            $this->addOption(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        }
        else if($mode == 'warning')
        {
            $this->addOption(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        }
        else
        {
            $this->addOption(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this;
    }

    /**
     * Set error mode to silent.
     *
     * @return $this
     */
    public function errorModeSilent()
    {
        return $this->setErrorMode('silent');
    }

    /**
     * Set error mode to warning.
     *
     * @return $this
     */
    public function errorModeWarning()
    {
        return $this->setErrorMode('warning');
    }

    /**
     * Set error mode to exception.
     *
     * @return $this
     */
    public function errorModeException()
    {
        return $this->setErrorMode('exception');
    }

    /**
     * Specifies the case of the column names.
     *
     * @return $this
     */
    public function lowercase()
    {
        return $this->addOption(PDO::ATTR_CASE, PDO::CASE_LOWER);
    }

    /**
     * Display column names as returned by the database.
     *
     * @return $this
     */
    public function natural()
    {
        return $this->addOption(PDO::ATTR_CASE, PDO::CASE_NATURAL);
    }

    /**
     * Causes column names to uppercase.
     *
     * @return $this
     */
    public function uppercase()
    {
        return $this->addOption(PDO::ATTR_CASE, PDO::CASE_UPPER);
    }

    /**
     * Convert numeric values into string.
     *
     * @param bool $stringify
     * @return $this
     */
    public function stringify(bool $stringify = true)
    {
        return $this->addOption(PDO::ATTR_STRINGIFY_FETCHES, $stringify);
    }

    /**
     * Converts empty string to null.
     *
     * @return $this
     */
    public function setEmptyStringToNull()
    {
        return $this->addOption(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
    }

    /**
     * Converts null to empty string.
     *
     * @return $this
     */
    public function setNullToEmptyString()
    {
        return $this->addOption(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
    }

    /**
     * Set dsn string segments.
     *
     * @return void
     */
    abstract public function dsn();
}