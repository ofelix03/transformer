<?php

namespace Ofelix03\Transformer;

/**
 * @author Felix Otoo <ofelix03@gmail.com>
 * @license [<url>] MIT
 */

class KeysBag {

	private $keys = array();

	function __construct(array $keys = array()) {
		$this->keys = $keys;
	}


	/**
	 * Checks whether a key exists
	 * 
	 * @param  string  $key 
	 * @return boolean      
	 */
	function hasKey($key) {
		return in_array($key, $this->keys);
	}

	/**
	 * Checks if there is a key with the given index
	 * @param  int  $index 
	 * @return boolean        
	 */
	function hasIndex($index) {
		if (isset($this->keys[$index])) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the total number of keys in the KeyBag
	 * 
	 * @return integer
	 */
	function count() {
		return count($this->keys);
	}

	/**
	 * Compares the keys in this KeyBag against another to see if 
	 * they are equal in length
	 * 
	 * @param  self    $keys 
	 * @return boolean       
	 */
	function hasEqualLength(self $keys) {
		if ($this->count() == $keys->count()) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the index of a key
	 * 
	 * @param  integer $key 
	 * @return integer
	 */
	function getIndex($key) {
		$position = null;
		foreach($this->keys as $index => $requestKey) {
			if ($requestKey == $key) {
				$position = $index;
				break;
			}
		}

		return $position;
	}


	/**
	 * Returns the key for a specified index
	 * 		
	 * @param  integer $index 
	 * @return  mixed      
	 */
	function get($index) {
		if (!isset($this->keys[$index])) {
			throw new \Exception('Key not found in key list');
		}

		return $this->keys[$index];
	}

	/**
	 * Returns all the keys in the  KeyBag
	 * 
	 * @return array
	 */
	function all() {
		return $this->keys;
	}
}
