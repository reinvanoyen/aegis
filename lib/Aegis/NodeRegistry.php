<?php

namespace Aegis;

class NodeRegistry
{
	private static $registeredNodes = [
		'Aegis\\Node\\ExtendNode',
		'Aegis\\Node\\BlockNode',
		'Aegis\\Node\\IfNode',
		'Aegis\\Node\\RawNode',
		'Aegis\\Node\\ForNode',
		'Aegis\\Node\\IncludeNode',
		'Aegis\\Node\\PrintNode',
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