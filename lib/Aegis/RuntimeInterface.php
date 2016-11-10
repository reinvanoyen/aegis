<?php

namespace Aegis;

interface RuntimeInterface
{
	public function __construct(NodeCollectionInterface $nodeCollection);
	public function getNodeCollection();
	public function set($v, $k);
}
