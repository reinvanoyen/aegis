<?php

namespace Aegis;

class NodeRegistry
{
	private static $registeredNodes = [
		'Aegis\\Node\\ExpressionNode',
		'Aegis\\Node\\ExtendNode',
		'Aegis\\Node\\BlockNode',
		'Aegis\\Node\\IfNode',
		'Aegis\\Node\\LoopNode',
		'Aegis\\Node\\RawNode',
		'Aegis\\Node\\ForNode',
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