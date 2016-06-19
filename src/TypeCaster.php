<?php

namespace Ofelix03\Transformer;

/**
 * @author Felix Otoo <ofelix03@gmail.com>
 * @license [<url>] MIT
 */

class TypeCaster {

	const CAST_TYPES = [
		'integer' => 'int',
		'string' => 'string',
		'float' => 'float',
		'bool' => 'bool',
		'array' => 'array',
	];

	static function cast($value, string $type) {
		if (is_null($value)) {
			throw new \InvalidArgumentException('A value is need for casting. First arugment should be a value to be casted');
		}

		if (is_null($type)) {
			throw new \InvalidArgumentException('A type to cast the value to is needed, none given');
		}

		if (static::CAST_TYPES['integer'] == $type) {
			return (int) $value;
		} else if (static::CAST_TYPES['string'] === $type) {
			return (string) $value;
		} else if (static::CAST_TYPES['bool'] === $type) {
			return (bool) $value;
		} else if (static::CAST_TYPES['array'] === $type) {
			return (array) $value;
		}
	}
}


