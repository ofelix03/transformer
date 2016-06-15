<?php

namespace Ofelix03\Transformer;

class TypeCaster {

	const CAST_TYPES = [
		'integer' => 'int',
		'string' => 'string',
		'float' => 'float',
		'bool' => 'bool',
		'array' => 'array',
	];

	static function cast($value, $type) {
		if (is_null($value)) {
			throw new \InvalidArgumentException;
		}

		if (is_null($type)) {
			throw new \InvalidArgumentException;
		}

		if (static::CAST_TYPES['integer'] == $type) {
			echo "casting integer\n";
			return (int) $value;
		} else if (static::CAST_TYPES['string'] === $type) {
			echo "casting string\n";
			return (string) $value;
		} else if (static::CAST_TYPES['bool'] === $type) {
			echo "casting bool\n";
			return (bool) $value;
		} else if (static::CAST_TYPES['array'] === $type) {
			echo "casting array\n";
			return (array) $value;
		}
	}
}


