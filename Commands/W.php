<?php

/**
 * @author Carhugar1
 */

include_once '/Patterns/Command.php';
 
class W extends Command {
	
	// <---- Constructors ---->
	
	/**
	 * Sets up the phrase for calling
	 */
	function __construct() {
		
		parent::__construct('W', 1);
		
	} // end __construct()
	
	
	
	// <---- private functions ---->
	
	/**
	 * Ran when the phrase has been called
	 */
	function run(Observable $observable) {
		
		$observable->command = 'BRIEF';
		$observable->message = array();
		
		$observable->notify();
		
		$observable->notice($observable->nick, 'Commands: ' . $observable->message[10]);
		
	} // end run()
	
	/**
	 * Ran when help about the command is requested
	 * @returns Returns a String with the help message
	 */
	function help() {
		
		return 'W lists all the commands for Warmachine.';
		
	} // end help()
	
}

?>