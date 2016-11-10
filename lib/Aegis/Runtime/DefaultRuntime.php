<?php

namespace Aegis\Runtime;

use Aegis\NodeCollectionInterface;
use Aegis\RuntimeInterface;

class DefaultRuntime implements RuntimeInterface
{
	private $nodeCollection;
    private $vars = [];
    private $blocks = [];
    public $functions = [];

    public function __construct(NodeCollectionInterface $nodeCollection)
    {
    	$this->nodeCollection = $nodeCollection;
    }

    public function getNodeCollection()
    {
        return $this->nodeCollection;
    }

	public function set($k, $v)
    {
        $this->vars[$k] = $v;
    }

    public function __get($k)
    {
        if (!isset($this->vars[$k])) {
            throw new UndefinedVariable($k);
        }

        return $this->vars[$k];
    }

    public function setBlock($id, $callable)
    {
        $this->blocks[$id] = [$callable];
    }

    public function appendBlock($id, $callable)
    {
        $this->blocks[$id][] = $callable;
    }

    public function prependBlock($id, $callable)
    {
        array_unshift($this->blocks[$id], $callable);
    }

    public function getBlock($id)
    {
        foreach ($this->blocks[$id] as $callable) {
            $callable();
        }
    }

    public function setFunction($funcName, $callable)
    {
        $this->functions[$funcName] = $callable;
    }
}
