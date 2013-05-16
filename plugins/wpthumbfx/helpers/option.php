<?php
/*
	Class: OptionAtomicPressHelper
		Option helper class, store option data
*/
class OptionAtomicPressHelper extends AtomicPressHelper {

    /*
		Variable: prefix
			Option prefix.
    */
	protected $prefix;

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($atomicpress) {
		parent::__construct($atomicpress);

		// set prefix
		$this->prefix = 'atomicpress_';
	}

	/*
		Function: get
			Get a value from data

		Parameters:
			$name - String
			$default - Mixed
		Returns:
			Mixed
	*/
	public function get($name, $default = null) {
		return get_option($this->prefix.$name, $default);
	}

 	/*
		Function: set
			Set a value

		Parameters:
			$name - String
			$value - Mixed

		Returns:
			Void
	*/
	public function set($name, $value) {
		update_option($this->prefix.$name, $value);
	}

}