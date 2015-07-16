<?php

/**
 * @author Carhugar1
 */
 
include_once '/IRCBot.php';
include_once 'Observer.php';
 
abstract class Command extends Observer {
	
	
	// <---- private variables ---->
	
	/*
	 * Holds the phrase used to activate this command
	 */
	private $phrase = array();
	
	/*
	 * The access level needed to run the command
	 */
	private $level; // not implemented
	
	
	
	// <---- Constructors ---->
	
	/**
	 * Sets up the phrase for calling
	 */
	function __construct($phrase_in, $level_in) {
		
		$this->phrase = $phrase_in;
		$this->level = $level_in;
		
	}
	
	
	
	// <---- public functions ---->
	
	/**
	 * Updates the Observer in some way, this is what gets called by the Observable object
	 */
	public function update(Observable $observable) {
		
		// get the access level of the user
		$query = "Select a.access_level from waccess_user_level a 
					Where a.username = '" . $observable->nick . "'";
		$result = mysqli_query($observable->database, $query);
		$accesslvl = mysqli_fetch_array($result)['access_level'];
		
		// if the username has no access level set it as 1 the base level
		if($accesslvl == null) {
			
			$accesslvl = 1;
			
		}
		
				
		// Run the command
		if(in_array($observable->command, $this->phrase) && $accesslvl >= $this->level) {
			
			if(isset($observable->message[0])) {
			
				if(in_array($observable->message[0], array('help', '?'))) {
				
					$observable->notice($observable->nick, $this->help());
				
				}
				
				else {
					
					$this->run($observable);
					
				}
				
			}
			
			else {
				
				$this->run($observable);
				
			}
			
		}
		
		else if($observable->command == 'BRIEF' && $accesslvl >= $this->level) {
			
			$this->brief($observable);
			
		}
		
	} // end update()
	
	
	
	// <---- private functions ---->
	
	/**
	 * Ran when the phrase has been called
	 */
	abstract function run(Observable $observable); 
	 
	/**
	 * Ran when help about the command is requested
	 * @returns Returns a String with the help message
	 */
	abstract function help();
	
	/**
	 * Modifies the observable's message to show the command 
	 */
	private function brief(Observable $observable) {
		
		@$observable->message[$this->level] .= implode(' ' ,$this->phrase) . ' ';
		
	}
	
}

?>