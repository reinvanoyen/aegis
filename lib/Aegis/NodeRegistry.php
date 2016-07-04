<?php

namespace Aegis;

class NodeRegistry
{
	private static $registeredNodes = [
		'Aegis\\Node\\IfNode',
		'Aegis\\Node\\ForNode',
		'Aegis\\Node\\BlockNode',
		'Aegis\\Node\\ExtendNode',
		'Aegis\\Node\\IncludeNode',
		'Aegis\\Node\\PrintNode',
		'Aegis\\Node\\RawNode',
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