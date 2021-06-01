<?php

namespace Graphite\Component\Neutrino;

abstract class DriverSupport
{
    /**
     * Register supported database drivers.
     *
     * @var array
     */
    protected static $drivers = [
        'sqlsrv' => \Graphite\Component\Neutrino\Drivers\SQLServer::class,
        'mysql'  => \Graphite\Component\Neutrino\Drivers\MySQL::class,
    ];

    /**
     * Determine if driver is supported.
     *
     * @return bool
     */
    public function hasDriverSupport()
    {
        return self::supported($this->driver);
    }

    /**
     * Determine if driver is supported.
     *
     * @param string $key
     * @return bool
     */
    protected static function supported(string $key)
    {
        return array_key_exists($key, self::$drivers);
    }

    /**
     * Return all supported drivers.
     *
     * @return array
     */
    public static function getSupportedDrivers()
    {
        return array_keys(self::$drivers);
    }
}