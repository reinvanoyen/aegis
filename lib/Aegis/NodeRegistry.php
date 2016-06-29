<?php

namespace Aegis;

class NodeRegistry
{
	private static $registeredNodes = [
		'Aegis\\Node\\ExtendNode',
		'Aegis\\Node\\BlockNode',
		'Aegis\\Node\\IfNode',
		'Aegis\\Node\\LoopNode',
		'Aegis\\Node\\RawNode',
		'Aegis\\Node\\ForNode',
		'Aegis\\Node\\Expression',
		/*
		'Aegis\\Node\\LoopNode',
		'Aegis\\Node\\Number',
		*/
	];

	public static function register( $mixed )
	{
		if( is_array( $mixed ) ) {
			foreach( $mixed as $classname ) {
				static::register( $classname );
			}
		} else {
			static::$registeredNodes[] = $mixed;
		}
	}

	public static function getNodes()
	{
		return static::$registeredNodes;
	}
}