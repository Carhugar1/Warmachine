<?php

/**
 * @author Carhugar1
 */

include_once '/Patterns/Command.php';
 
class Waccess extends Command {
	
	// <---- Constructors ---->
	
	/**
	 * Sets up the phrase for calling
	 */
	function __construct() {
		
		parent::__construct(array('Waccess'), 8);
		
	} // end __construct()
	
	
	
	// <---- private functions ---->
	
	/**
	 * Ran when the phrase has been called
	 */
	function run(Observable $observable) {
		
		if (isset($observable->message[0])) {
			
			// get the information of the given username
			$query = "Select * from waccess_user_level a 
						Where a.username = '" . $observable->message[0] . "'";
			$result = mysqli_query($observable->database, $query);
			
			if (isset($observable->message[1])) {
				
				// get the access level of the user
				$query2 = "Select a.access_level from waccess_user_level a 
							Where a.username = '" . $observable->nick . "'";
				$result2 = mysqli_query($observable->database, $query2);
				
				// get the given username's access level
				$usernamelvl = mysqli_fetch_array($result)['access_level'];
				
				if($usernamelvl == null) {// null check
					$usernamelvl = 1;
				}
				
				// get the user's access level
				$userlvl = mysqli_fetch_array($result2)['access_level'];
				
				// Does the user have a higher access level?
				if($userlvl > $observable->message[1] && $userlvl > $usernamelvl) {
					
					// delete the old stuff ( works even if the person isn't in the database )
					$query = "Delete from waccess_user_level
								Where username = '" . $observable->message[0] . "'";
					mysqli_query($observable->database, $query);
					
					// add the new stuff
					$query = "Insert into waccess_user_level (access_level, username, set_by) 
								Values (" . $observable->message[1] . ", '" . $observable->message[0] . "', '" . $observable->nick . "')";
					mysqli_query($observable->database, $query);
					
					// get the new information of the given username
					$query = "Select * from waccess_user_level a 
								Where a.username = '" . $observable->message[0] . "'";
					$result = mysqli_query($observable->database, $query);
					$row = mysqli_fetch_array($result);
					
					// print out the new information
					$observable->notice($observable->nick, $row['username'] . 
										' has access level ' . $row['access_level'] . 
										' set by ' . $row['set_by'] . 
										' on ' . $row['timestamp']);
					
				}
				
				else {
					
					$observable->notice($observable->nick, 'Error: Your access level isn\'t high enough to perform that action');
					
				}
			
			}
			
			else {
			
				while($row = mysqli_fetch_array($result)) {
				
					$observable->notice($observable->nick, $row['username'] . 
										' has access level ' . $row['access_level'] . 
										' set by ' . $row['set_by'] . 
										' on ' . $row['timestamp']);
			
				}
				
			}
			
		}
		
		else {
			
			$observable->notice($observable->nick, 'Waccess [ User ] ( access level )');
			
		}
		
	} // end run()
	
	/**
	 * Ran when help about the command is requested
	 * @returns Returns a String with the help message
	 */
	function help() {
		
		return 'Waccess sets a user\'s access level. ' . 
				'A user\'s access level decides the commands that are available to that user. ' .
				'Suggested levels: 1 anyone, 2-4 basic, 5-7 basic+, 8-9 channel admin, 10 bot admin.';
		
	} // end help()
	
}

?>