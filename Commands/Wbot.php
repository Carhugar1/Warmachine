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
		
		parent::__construct(array('Wbot'), 10);
		
	} // end __construct()
	
	
	
	// <---- private functions ---->
	
	/**
	 * Ran when the phrase has been called
	 */
	function run(Observable $observable) {
		
		if($observable->nick == 'Carhugar1') {
		
			if(isset($observable->message[0])) {
			
				if($observable->message[0] == 'restart') {
				
					echo "<meta http-equiv=\"refresh\" content=\"5\">";
					$observable->quit('Restart Command Executed');
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
					
						// attach to bot
						include_once $observable->message[1] . '.php';
						eval('$observable->attach(new ' . $observable->message[1] . '());');
					
						// add to database
						$query = "Insert into wbot_attach (command, user) Values ('" . $observable->message[1] . "', '" . $observable->nick . "')";
						echo mysqli_query($observable->database, $query);
					
					}			
				
				} // end attach
			
				else if($observable->message[0] == 'detach') {
				
					if(array_key_exists(1, $observable->message)) {
					
						// detach from bot
						eval('$observable->detach(new ' . $observable->message[1] . '());');
					
						// remove from database
						$query = "Delete from wbot_attach Where command = '" . $observable->message[1] . "'";
						echo mysqli_query($observable->database, $query);
					
					}			
				
				} // end detach
			
				else if($observable->message[0] == 'boot') {
				
					$query = "Select command from wbot_attach";
					$result = mysqli_query($observable->database, $query);
		
					while($row = mysqli_fetch_array($result)) {
					
						include_once $row['command'] . '.php';
						eval('$observable->attach(new ' . $row['command'] . '());');
					
					}
				
				} // end boot
			
				else {
				
					$observable->notice($observable->nick, 'Wbot [ shutdown | restart | join | leave ] ( channel )');
				
				}
				
			}
			
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