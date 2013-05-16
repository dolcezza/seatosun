<?php
/*
	Class: DataAtomicPressHelper
		Data helper class.
*/
class DataAtomicPressHelper extends AtomicPressHelper {

	/*
		Function: __construct
			Class Constructor.
	*/
	public function __construct($atomicpress) {
		parent::__construct($atomicpress);

		// load class
		require_once($this['path']->path('classes:data.php'));
	}

	/*
		Function: create
			Retrieve a data object

		Parameters:
			$data - Data
			$format - Data format

		Returns:
			Mixed
	*/
	public function create($data = array(), $format = 'json') {
		
		// load data class
		$class = $format.'AtomicPressData';

		return new $class($data);
	}
	
}