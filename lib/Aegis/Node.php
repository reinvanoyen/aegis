<?php

namespace Aegis;

abstract class Node
{
    private $parent = null;
    private $children = [];
    private $attributes = [];
    private $isAttribute = false;

    public function setAttribute(Node $n)
    {
        $n->isAttribute = true;
        $this->attributes[] = $n;
    }

    public function isAttribute()
    {
        return $this->isAttribute;
    }

    public function getAttribute($i)
    {
        return isset($this->attributes[ $i ]) ? $this->attributes[ $i ] : null;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getParent()
    {
    	if (!$this->parent) {
    		throw new AegisError('Could not get parent node of node, because the node has no parent');
	    }

        return $this->parent;
    }

    public function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

    public function getChild($i)
    {
    	if (!isset($this->children[$i])) {
		    throw new AegisError('Could not get child from node, because there\'s no child at index ' . $i );
	    }

        return $this->children[$i];
    }

    public function getLastChild()
    {
        $last = end($this->children);

	    if (!$last) {
		    throw new AegisError('Could not get last child from node, because the node has no children' );
	    }

        return $last;
    }

    public function removeChild($i)
    {
	    if (!isset($this->children[$i])) {
		    throw new AegisError('Could remove child from node, because there\'s no child at index ' . $i );
	    }

        unset($this->children[$i]);
    }

    public function removeLastChild()
    {
        $last = array_pop($this->children);

	    if (!$last) {
		    throw new AegisError('Could not remove last child from node, because the node has no children' );
	    }
    }

    public function getSiblings()
    {
        return $this->getParent()->getChildren();
    }

    public function insert(Node $node)
    {
        $this->children[] = $node;
    }

    public function getName()
    {
        return get_class($this);
    }

    abstract public function compile(CompilerInterface $compiler);
}
