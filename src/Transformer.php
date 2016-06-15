<?php

namespace Ofelix03\Transformer;

class Transformer {

	/** @var \KeysBag The keys expected in the request data */
	protected $requestKeys;

	/** @var \KeysBag The keys expected to replace the keys in  the request fields */
	protected $morphKeys;

	/** @var array The request payload to be transformed */
	private $payload = [];

	/** @var array The transformed data with the morphed keys */
	private $morphedPayload = [];

	/** @var boolean Indicates whether the transformer has been runned on request payload */
	private $isTransformed = false;

	/** A delimiter used to attach type to morphKeys to indicate type casting */
	const TYPE_DELIMITER = ":";

	/**
	 * This flag is used to impose an equal in length constraint on the two keys used to 
	 * transformed the request data
	 * @var boolean
	 */
	protected $strict = false;

	function __construct(array $payload = [], array $requestKeys = [], $morphKeys = []) {
		$this->payload = $payload;

		if (count($requestKeys)) {
			$this->requestKeys = new KeysBag($requestKeys);
		} else if (method_exists($this, 'createRequestKeys')) {
			$this->requestKeys = new KeysBag($this->createRequestKeys());
		} else {
			$this->requestKeys = new KeysBag([]);
		}

		if (count($morphKeys)) {
			$this->morphKeys = new KeysBag($morphKeys);
		} else if (method_exists($this, 'createMorphKeys')) {
			$this->morphKeys = new KeysBag($this->createMorphKeys());
		} else {
			$this->morphKeys = new KeysBag([]);
		}

		$this->setIsTransform(false);
	}

	function isStrict() {
		return $this->strict;
	}

	function setStrict($bool = false) {
		$this->strict = $bool;

		return $this;
	}

	private function setIsTransform($bool = false) {
		$this->isTransformed = $bool;
	}

	private function isTransformed() {
		return $this->isTransformed;
	}

	/**
	 * Returns an array of keys expected from our request payload
	 * 
	 *
	 * @return array 
	 */
	function createRequestKeys() {
		return [];
	}

	/**
	 * Returns an array of keys expected to replace our request keys
	 * 
	 * @return array
	 */
	function createMorphKeys() {
		return [];
	}

	function setRequestKeys(array $keys) {
		if (count($keys)) {
			$this->requestKeys = new KeysBag($keys);
		}

		return $this;
	}

	function setMorphKeys(array $keys) {
		if (count($keys)) {
			$this->morphKeys = new KeysBag($keys);
		}

		return $this;
	}

	function setRequestPayload($payload) {
		$this->payload = $payload;
	}

	function compareKeys() {
		$compare = [];

		for($index = 0; $index < $this->requestKeys->count(); $index++) {
			$requestKey =  $this->requestKeys->get($index);
			$morphKey =  $this->morphKeys->hasIndex($index) ? $this->morphKeys->get($index) : null;

			$compare[$requestKey] = $morphKey;
		}

		return $compare;
	}

	function transform($payload = [], $strict = false) {
		$this->setIsTransform(true);

		if (count($payload)) {
			$this->payload = $payload;
		} else if (!count($this->payload)) {
			throw new \Exception('A paylaod is need for the transformation');
		}

		if ($this->requestKeys->count() == 0) {
			throw new \Exception('A request definition is need for the tranformation');
		}

		if ($this->morphKeys->count() == 0) {
			throw new \Exception('A morph definition is need for the transformation');
		}

		if ($this->isStrict() || $strict === true) {
			if (!$this->requestKeys->hasEqualLength($this->morphKeys)) {
				throw new \Exception("The request key and morph bags are not equal in length");
			}
		}

		foreach($this->payload as $key => $value) {
			if ($this->requestKeys->hasKey($key)) {
				$mKey = $this->morphKeys->get($this->requestKeys->getIndex($key));
				if ($this->hasTypeCast($mKey)) {
					list($mKey, $type) = explode(':', $mKey);
					$this->morphedPayload[$mKey] = TypeCaster::cast($mKey, $type);
				} else {
					$this->morphedPayload[$mKey] = $value;
				}
			} else {
				// Let keep the key as it was in the request payload 
				$this->morphedPayload[$key] = $value;
			}
		}

		return $this->morphedPayload;
	}

	private function hasTypeCast($key) {
		if (stripos($key, static::TYPE_DELIMITER)) {
			return true;
		} 

		return false;
	}

	function getMorphedData() {
		if ($this->isTransformed()) {
			return $this->morphedPayload;
		} else {
			throw new \Exception("You have to run " + __CLASS__ + "::" + __METHOD__ + " before calling this method");
		}
	}
}
