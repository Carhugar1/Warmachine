<?php

/**
 * @author Carhugar1
 */

include_once '/Patterns/Command.php';
 
class Whelp extends Command {
	
	// <---- Constructors ---->
	
	/**
	 * Sets up the phrase for calling
	 */
	function __construct() {
		
		parent::__construct(array('Whelp', 'W?'), 1);
		
	} // end __construct()
	
	
	
	// <---- private functions ---->
	
	/**
	 * Ran when the phrase has been called
	 */
	function run(Observable $observable) {
		
		// run the commands help method :P
		if(array_key_exists(0, $observable->message)) {
		
			$observable->command = $observable->message[0];
			$observable->message[0] = 'help';
		
			$observable->notify();
			
		}
		
		else {
			
			$observable->notice($observable->nick, 'Whelp [ command ]');
			
		}		
		
	} // end run()
	
	/**
	 * Ran when help about the command is requested
	 * @returns Returns a String with the help message
	 */
	function help() {
		
		return 'Retrieves help on the given command.';
		
	} // end help()
	
}

?>