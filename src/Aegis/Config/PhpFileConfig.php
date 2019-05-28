<?php

namespace Aegis\Config;

use Aegis\Contracts\ConfigInterface;

class PhpFileConfig implements ConfigInterface
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var array
     */
    private $data = [];

    public function __construct(string $filename)
    {
        $this->filename = $filename;

        $this->data = require $this->filename;
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }

        return null;
    }

    public function has($key): bool
    {
        return isset($this->data[$key]);
    }
}
