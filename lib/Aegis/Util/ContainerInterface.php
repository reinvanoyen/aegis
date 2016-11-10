<?php

namespace Aegis\Util;

interface ContainerInterface
{
	public function get($id);
	public function has($id);
}