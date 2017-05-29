<?php

namespace iamgold\doublesfs;

/**
 * This is a abstract class of storage.
 *
 * @author Eric Huang <iamgold0105@gmail.com>
 * @version 1.0.0
 */
abstract class AbstractStorage
{
    /**
     * @var array $_attributes
     */
    private $_attributes = [];

    /**
     * Construct
     *
     * @param array $config
     */
    public function __construct($config)
    {
        try {
            $this->validateConfig($config);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Setter
     *
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value) {
        $setter = 'set' . ucfirst(strtolower($name));
        if (method_exists($this, $setter))
            $this->$setter($value);
        elseif (property_exists($this, $name))
            $this->$name = $value;
        else
            $this->_attributes[$name] = $value;
    }

    /**
     * Get
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $getter = 'get' . ucfirst(strtolower($name));
        if (method_exists($this, $getter))
            return call_user_func($this, $getter);
        elseif (property_exists($this, $name))
            return $this->$name;
        elseif (isset($this->_attributes[$name]))
            return $this->_attributes[$name];
        else
            throw new Exception("Unknown property");
    }

    /**
     * Validate configuration
     *
     * @param array $config
     */
    abstract protected function validateConfig($config);
}
