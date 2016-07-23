<?php

namespace Ofelix03\Transformer;

/** 
 * @author Felix Otoo <ofelix03@gmail.com>
 * @license MIT Check the license file associated with this package for detials of 
 *          	the copyright agreements.
 */

 use DateTime;
 use InvalidArgumentException;


class TypeCaster {

	const CAST_TYPES = [
	'integer' => 'int',
	'string' => 'string',
	'float' => 'float',
	'double' => 'double',
	'bool' => 'bool',
	'array' => 'array',
	'dateTime' => 'dateTime',
	];

	static function cast($value, $type = "") {
		if (is_null($value)) {
			throw new InvalidArgumentException('A value is need for casting. First arugment should be a value to be casted');
		}

		if (is_null($type)) {
			throw new InvalidArgumentException('A type to cast the value to is needed, none given. Second argument must be a supported type');
		}

		if (static::CAST_TYPES['integer'] === $type) {
			return (int) $value;
		} else if (static::CAST_TYPES['string'] === $type) {
			return (string) $value;
		} else if (static::CAST_TYPES['bool'] === $type) {
			return (bool) $value;
		} else if (static::CAST_TYPES['array'] === $type) {
			return (array) $value;
		} else if (static::CAST_TYPES['float'] === $type  
			|| static::CAST_TYPES['double'] === $type) {
			return (double) $value;
		} else if (static::CAST_TYPES['dateTime'] === $type) {
			return new DateTime($value);
		}
	}
}


