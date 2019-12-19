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
    private $slots = [];

    /**
     * @var array
     */
    public $functions = [];

    /**
     * @var int
     */
    private $componentUId = 0;

    /**
     * @var int
     */
    private $previousComponentId = 0;

    /**
     * @var int
     */
    private $currentComponentId = 0;

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
     * Sets a slot
     *
     * @param $id
     * @param $callable
     * @return void
     */
    public function setSlot($id, $callable) : void
    {
        $this->slots[$this->currentComponentId][$id] = [$callable];
    }

    /**
     * Yields a slot
     *
     * @param $id
     * @param $callable
     * @return void
     */
    public function yieldSlot($id, $callable) : void
    {
        if (! isset($this->slots[$this->currentComponentId][$id])) {
            $this->slots[$this->currentComponentId][$id] = [$callable];
        }
    }

    /**
     * Executes the contents of a block
     *
     * @param $id
     * @return void
     */
    public function getSlot($id) : void
    {
        foreach ($this->slots[$this->currentComponentId][$id] as $callable) {
            $callable();
        }
    }

    /**
     * Creates a new component context
     */
    public function createComponentContext()
    {
        $this->componentUId++;
        $this->previousComponentId = $this->currentComponentId;
        $this->currentComponentId = $this->componentUId;
    }

    /**
     * Rewinds the current component to the previous context
     */
    public function rewindComponentContext()
    {
        $this->currentComponentId = $this->previousComponentId;
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
