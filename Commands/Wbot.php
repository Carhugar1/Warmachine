<?php

/**
 * @author Carhugar1
 */

include_once '/Patterns/Command.php';
 
class Wbot extends Command {
	
	// <---- Constructors ---->
	
	/**
	 * Sets up the phrase for calling
	 */
	function __construct() {
		
		parent::__construct('Wbot', 10);
		
	} // end __construct()
	
	
	
	// <---- private functions ---->
	
	/**
	 * Ran when the phrase has been called
	 */
	function run(Observable $observable) {
		
		if($observable->nick == 'Carhugar1') {
			
			if($observable->message[0] == 'restart') {
				
				$observable->quit('Restart Command Executed');
				echo "<meta http-equiv=\"refresh\" content=\"5\">";
				exit;
				
			} // end restart
			
			else if($observable->message[0] == 'shutdown') {
				
				$observable->quit('Shutdown Command Executed');
				exit;
				
			} // end shutdown
			
			else if($observable->message[0] == 'join') {
				
				if(array_key_exists(1, $observable->message)) {
					
					$observable->join($observable->message[1]);
					
				}
				
			} // end join
			
			else if($observable->message[0] == 'leave') {
				
				if(array_key_exists(1, $observable->message)) {
					
					$observable->part($observable->message[1]);
					
				}
				
				else {
					
					$observable->part($observable->channel);
					
				}
				
			} // end leave
			
			else if($observable->message[0] == 'attach') {
				
				if(array_key_exists(1, $observable->message)) {
					
					include_once $observable->message[1] . '.php';
					eval('$observable->attach(new ' . $observable->message[1] . '());');
					
				}			
				
			} // end attach
			
			else if($observable->message[0] == 'detach') {
				
				if(array_key_exists(1, $observable->message)) {
					
					eval('$observable->detach(new ' . $observable->message[1] . '());');
					
				}			
				
			} // end detach
			
			else {
				
				$observable->notice($observable->nick, 'Wbot [ shutdown | restart | join | leave ]');
				
			}
			
		}	
		
	} // end run()
	
	/**
	 * Ran when help about the command is requested
	 * @returns Returns a String with the help message
	 */
	function help() {
		
		return 'Wbot is an administrative command to control Warmachine.';
		
	} // end help()
	
}

?>