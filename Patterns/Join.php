<?php

/**
 * @author Carhugar1
 */

include_once '/Patterns/Command.php';
 
abstract class Join extends Command {
	
	// <---- Constructors ---->
	
	/**
	 * Sets up the phrase for calling
	 */
	function __construct($phrase_in, $level_in) {
		
		$this->phrase = $phrase_in;
		$this->level = $level_in;
		
	} // end __construct()
	
	
	
	// <---- public functions ---->
	
	/**
	 * Updates the Observer in some way, this is what gets called by the Observable object
	 */
	public function update(Observable $observable) {
		
		// get the access level of the user
		$query = "Select a.access_level from waccess_user_level a 
					Where a.username = '" . $observable->nick . "'";
		$result = mysqli_query($observable->database, $query);
		$this->accesslvl = mysqli_fetch_array($result)['access_level'];
		
		// if the username has no access level set it as 1 the base level
		if($this->accesslvl == null) {
			
			$this->accesslvl = 1;
			
		}
		
		
		// Run the code on the Join command
		if($observable->command == 'JOIN') {
			
			$this->run();
			
		}
		
				
		// Run the help for the command
		else if(in_array($observable->command, $this->phrase) && $this->accesslvl >= $this->level) {
			
			$observable->notice($observable->nick, "(Join): " . $this->help());
		
		}
		
		// add it to the list of commands
		else if($observable->command == 'BRIEF' && $this->accesslvl >= $this->level) {
			
			$this->brief($observable);
			
		}
		
	} // end update()
	
	/**
	 * Modifies the observable's message to show the command 
	 */
	private function brief(Observable $observable) {
		
		@$observable->message[$this->level] .= implode(' ' ,$this->phrase) . ' ';
		
	}
	
}

?>