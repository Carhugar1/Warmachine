<?php

/**
 * @author Carhugar1
 */
 
include_once 'IRCBot.php';
include_once 'Observer.php';
 
class Wbot extends Observer {
	
	/**
	 * Updates the Observer in some way, this is what gets called by the Observable object
	 */
	function update(Observable $observable) {
		
		if($observable->command == 'Wbot' && $observable->nick == 'Carhugar1') {
			
			if($observable->message[0] == 'restart') {
				
				$observable->quit('Restart Command Executed');
				echo "<meta http-equiv=\"refresh\" content=\"5\">";
				exit;
				
			}
			
			else if($observable->message[0] == 'shutdown') {
				
				$observable->quit('Shutdown Command Executed');
				exit;
				
			}
			
			else if($observable->message[0] == 'join') {
				
				$observable->join($observable->message[1]);
				
			}
			
			else if($observable->message[0] == 'leave') {
				
				if(array_key_exists(1, $observable->message)) {
					
					$observable->part($observable->message[1]);
					
				}
				
				else {
					
					$observable->part($observable->channel);
					
				}
				
			}
			
			else {
				
				$observable->notice($observable->nick, 'Wbot [shutdown, restart]');
				
			}
			
		}	
		
	}
	
}

?>