<?php

namespace Aegis\Runtime;

use Aegis\Contracts\RuntimeInterface;
use Aegis\Exception\UndefinedVariable;

/**
 * Class Runtime
 * @package Aegis\Runtime
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
class Runtime implements RuntimeInterface
{
    /**
     * @var array
     */
    private $vars = [];

    /**
     * @var array
     */
    private $blocks = [];

    /**
     * @var array
     */
    public $functions = [];

    /**
     * Sets a variable
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function set($key, $value) : void
    {
        $this->vars[$key] = $value;
    }

    /**
     * Gets a variable
     *
     * @param $key
     * @return mixed
     * @throws UndefinedVariable
     */
    public function __get($key)
    {
        if (!isset($this->vars[$key])) {
            throw new UndefinedVariable($key);
        }

        return $this->vars[$key];
    }

    /**
     * Sets a block
     *
     * @param $id
     * @param $callable
     * @return void
     */
    public function setBlock($id, $callable) : void
    {
        $this->blocks[$id] = [$callable];
    }

    /**
     * Appends a callable to a block
     *
     * @param $id
     * @param $callable
     * @return void
     */
    public function appendBlock($id, $callable) : void
    {
        $this->blocks[$id][] = $callable;
    }

    /**
     * Prepends a callable to a block
     *
     * @param $id
     * @param $callable
     * @return void
     */
    public function prependBlock($id, $callable) : void
    {
        array_unshift($this->blocks[$id], $callable);
    }

    /**
     * Executes the contents of a block
     *
     * @param $id
     * @return void
     */
    public function getBlock($id) : void
    {
        foreach ($this->blocks[$id] as $callable) {
            $callable();
        }
    }

    /**
     * Sets a function
     *
     * @param $funcName
     * @param $callable
     * @return void
     */
    public function setFunction($funcName, $callable) : void
    {
        $this->functions[$funcName] = $callable;
    }
}