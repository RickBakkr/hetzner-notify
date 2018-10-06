<?php

namespace HetznerNotify;

class Config
{
    const CONFIG_FILE = 'config.php';
    protected $_config = array();

    public function __construct($loadConfig = false)
    {
        if ($loadConfig) {
            if (!$this->load()){
                echo 'config file '.self::CONFIG_FILE.' not found.';
                exit;
            }
        }
    }

    /**
     * @return bool
     */
    public function load()
    {
        if (file_exists(self::CONFIG_FILE)) {
            $config = include self::CONFIG_FILE;
            if (is_array($config)) {
                $this->_config = $config;
                return true;
            }
        }

        return false;
    }

    /**
     * @param null $key
     * @return mixed|null
     */
    public function get($key = null)
    {
        if ($key === null) {
            return $this->_config;
        }

        if (array_key_exists($key, $this->_config)) {
            return $this->_config[$key];
        }

        return null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->_config[$key] = $value;
    }
}
