<?php

/**
 * @author Carhugar1
 */
 
include_once '/IRCBot.php';
include_once 'Observer.php';
 
abstract class Command extends Observer {
	
	//// <---- private variables ---->
	
	/*
	 * Holds the phrase used to activate this command
	 */
	private $phrase;
	
	/*
	 *
	 */
	private $level;
	
	
	
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
		
		if($observable->command == $this->phrase) {
			
			if($observable->message[0] == 'help' || $observable->message[0] == '?') {
				
				$observable->notice($observable->nick, $this->help());
				
			}
			
			else {
				
				$this->run($observable);
				
			}
			
		}
		
		else if($observable->command == 'BRIEF') {
			
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
		
		$observable->message[$this->level] .= $this->phrase . ' ';
		
	}
	
}

?>