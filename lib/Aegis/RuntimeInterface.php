<?php

namespace Aegis;

/**
 * Interface RuntimeInterface
 * @package Aegis
 */
interface RuntimeInterface
{
    /**
     * RuntimeInterface constructor.
     * @param NodeCollectionInterface $nodeCollection
     */
    public function __construct(NodeCollectionInterface $nodeCollection);

    /**
     * @return mixed
     */
    public function getNodeCollection();

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value);
}
