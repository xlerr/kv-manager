<?php

namespace kvmanager;

class Config
{
    use ConfigTrait;

    protected $value;

    protected $path;

    public function __construct($raw, $path)
    {
        $this->path  = $path;
        $this->value = $raw;
    }

    public function value()
    {
        return $this->value;
    }

    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $name
     * @return Config
     * @throws ConfigException
     */
    public function __get($name)
    {
        if (!is_array($this->value)) {
            throw new ConfigException(vsprintf('The type of [%s] is %s.', [
                $this->path,
                gettype($this->value),
            ]));
        }
        if (!array_key_exists($name, $this->value)) {
            throw new ConfigException(vsprintf('%s does not exist in [%s].', [
                $name,
                $this->path,
            ]));
        }
        return new static($this->value[$name], $this->path . '.' . $name);
    }
}
