<?php

namespace Aegis;

/**
 * Class Node
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */

abstract class Node
{
    private $parent = null;
    private $children = [];
    private $attributes = [];
    private $isAttribute = false;

	/**
	 * @param Node $node
	 */
    public function setAttribute(Node $node)
    {
        $node->isAttribute = true;
        $this->attributes[] = $node;
    }

	/**
	 * @return bool
	 */
    public function isAttribute()
    {
        return $this->isAttribute;
    }

	/**
	 * @param $index
	 * @return mixed|null
	 */
    public function getAttribute($index)
    {
        return isset($this->attributes[$index]) ? $this->attributes[$index] : null;
    }

	/**
	 * @return array
	 */
    public function getAttributes()
    {
        return $this->attributes;
    }

	/**
	 * @return array
	 */
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

	/**
	 * @param Node $parent
	 */
    public function setParent(Node $parent)
    {
        $this->parent = $parent;
    }

	/**
	 * @param $index
	 * @return mixed
	 * @throws AegisError
	 */
    public function getChild($index)
    {
        if (!isset($this->children[$index])) {
            throw new AegisError('Could not get child from node, because there\'s no child at index '.$i);
        }

        return $this->children[$index];
    }

	/**
	 * @return mixed
	 * @throws AegisError
	 */
    public function getLastChild()
    {
        $last = end($this->children);

        if (!$last) {
            throw new AegisError('Could not get last child from node, because the node has no children');
        }

        return $last;
    }

	/**
	 * @param $index
	 * @throws AegisError
	 */
    public function removeChild($index)
    {
        if (!isset($this->children[$index])) {
            throw new AegisError('Could remove child from node, because there\'s no child at index '.$index);
        }

        unset($this->children[$index]);
    }

	/**
	 * @throws AegisError
	 */
    public function removeLastChild()
    {
        $last = array_pop($this->children);

        if (!$last) {
            throw new AegisError('Could not remove last child from node, because the node has no children');
        }
    }

	/**
	 * @return mixed
	 */
    public function getSiblings()
    {
        return $this->getParent()->getChildren();
    }

	/**
	 * @param Node $node
	 */
    public function insert(Node $node)
    {
        $this->children[] = $node;
    }

	/**
	 * @return string
	 */
    public function getName()
    {
        return get_class($this);
    }

	/**
	 * @param CompilerInterface $compiler
	 * @return mixed
	 */
    abstract public function compile(CompilerInterface $compiler);
}
