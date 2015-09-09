<?php

/**
 * @author Carhugar1
 */

include_once '/Patterns/Join.php';
 
class Wmode extends Join {
	
	
	// <---- private variables ---->
	
	/*
	 * Array for the mode characters 
	 */
	private $char_level = array(
							5 => '+v',
								 '+v',
								 '+v',
								 '+h',
								 '+h',
								 '+o',
							99 => 'o' );

	

	// <---- Constructors ---->
	
	/**
	 * Sets up the phrase for calling
	 */
	function __construct() {
		
		parent::__construct(array('Wmode'), 9);
		
	} // end __construct()
	
	
	
	// <---- private functions ---->
	
	/**
	 * Ran when the Join command has been called
	 */
	function run(Observable $observable) {
		
		if($this->accesslvl > 4) {
		
			$ops = $this->char_level[$this->accesslvl];
		
			$observable->mode($observable->channel, $ops . ' ' . $observable->nick);
		
		}
		
	} // end run()
	
	/**
	 * Ran when help about the command is requested
	 * @returns Returns a String with the help message
	 */
	function help() {
		
		return 'Wmode sets the level of privileges on channels based on the user\'s access level.';
		
	} // end help()
	
}

?>