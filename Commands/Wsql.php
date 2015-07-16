<?php

/**
 * @author Carhugar1
 */

include_once '/Patterns/Command.php';
 
class Wsql extends Command {
	
	// <---- Constructors ---->
	
	/**
	 * Sets up the phrase for calling
	 */
	function __construct() {
		
		parent::__construct(array('Wsql'), 10);
		
	} // end __construct()
	
	
	
	// <---- private functions ---->
	
	/**
	 * Ran when the phrase has been called
	 */
	function run(Observable $observable) {
		
		if(isset($observable->message[0])) {
			
			$query = implode(" ", $observable->message);
			$result = mysqli_query($observable->database, $query);
			
			// print the column names
			$row = mysqli_fetch_assoc($result);
			$observable->notice($observable->nick, implode(" ", array_keys($row)));
			
			while($row != null) {
				
				// print row
				echo implode(" ", $row) . '<br>';
				$observable->notice($observable->nick, implode(" ", $row));
				
				// get next row
				$row = mysqli_fetch_assoc($result);
			
			}
			
		}
		
		else {
			
			$observable->notice($observable->nick, 'Wsql [ SQL statement ]');
			
		}
		
	} // end run()
	
	/**
	 * Ran when help about the command is requested
	 * @returns Returns a String with the help message
	 */
	function help() {
		
		return 'Wsql queries the statement provided on the database.';
		
	} // end help()
	
}

?>