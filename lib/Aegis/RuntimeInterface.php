<?php

namespace Aegis;

/**
 * Interface RuntimeInterface
 * @package Aegis
 * @author Rein Van Oyen <reinvanoyen@gmail.com>
 */
interface RuntimeInterface
{
    /**
     * RuntimeInterface constructor.
     * @param NodeCollectionInterface $nodeCollection
     */
    public function __construct(NodeCollectionInterface $nodeCollection);

    /**
     * @return NodeCollectionInterface
     */
    public function getNodeCollection() : NodeCollectionInterface;

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value);
}
