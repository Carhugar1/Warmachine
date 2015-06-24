<?php

/**
 * @author Carhugar1
 */

include_once 'Patterns/Observer.php';
include_once 'Commands/Wbot.php';

class IRCBot extends Observable {
	
	/* 
	 * Needs a configuration file in the format:
	 *  $config 	=  array( 
     *  'server' 	=> 'string', 
     *  'port'   	=> number, 
     *  'channel' 	=> 'string',
     *  'nick'  	=> 'string', 
     *  'pass'  	=> 'string', 
     * );
	 *
	 */
	 
	 

	// <---- private variables ---->
	
	/*
	 * Holds the connection to IRC
	 */
	private $socket;
	
	/*
	 * Holds the messages to and from IRC
	 */
	private $socket_msg = array();
	
	/*
	 * The Bot's Observers
	 */
	private $observers = array();

	
	
	// <---- public variables ---->
	
	/*
	 * The channel the command was sent on (can be a user name)
	 */
	public $channel;
	
	/*
	 * The command sent
	 */
	public $command;
	
	/*
	 * The name of the user that send the command
	 */
	public $nick;
	
	/*
	 * The message that followed the command in array format
	 */
	public $message = array();
	

	
	// <---- Constructors ---->
	
	/**
	 * Sets up the connection to IRC
	 */
	function __construct($config) {
		
		$this->socket = fsockopen($config['server'], $config['port']);
		$this->login($config);
		$this->attach(new Wbot());
		$this->parse();
		
	}
	
	
	
	// <---- AutoLoader ---->
	
	/**
	 * Includes any needed files [DOESN'T WORK]
	 */
	/*function __autoload($class) {
		
		include $class . '.php';
		
	}*/
	
	//spl_autoload_register('AutoLoader');
	
	
	
	// <---- private functions ---->
	
	/**
	 * Logs into IRC 
	 */
	private function login($config) {
		
		fputs($this->socket, 'NICK ' . $config['nick'] . "\r\n");
		echo '<b>NICK ' . $config['nick'] . '</b><br>';
		
		fputs($this->socket, 'USER ' . $config['nick'] . ' 0 * :' . $config['nick'] . "\r\n");
		echo '<b>USER ' . $config['nick'] . ' 0 * :' . $config['nick'] . '</b><br>';
		
		$this->msg('NickServ' , 'IDENTIFY ' . $config['pass']);
		
		$this->join($config['channel']);
		
	}
	
	/**
	 * Recursively parses the data sent and notifies the observers
	 * Also handles PING PONG 
	 */
	private function parse() {
		
		$data = fgets($this->socket, 256);
		
		echo nl2br($data);
		
		flush();
		
		$this->socket_msg = explode(' ', $data);
		
		// PING PONG
		if($this->socket_msg[0] == 'PING') {
			
            fputs($this->socket, 'PONG ' . $this->socket_msg[1] . "\r\n");
			echo '<b>PONG ' . $this->socket_msg[1] . '</b><br>';
			
        }
		
		// Message
		if($this->socket_msg[1] == 'PRIVMSG') {
		
			// Get the data
			$this->channel = $this->socket_msg[2];
		
			$this->command = str_replace(array(chr(10), chr(13), ':'), '', $this->socket_msg[3]);
		
			$this->nick = str_replace(':', '', strtok($this->socket_msg[0], '!'));
		
			$this->message = str_replace(array(chr(10), chr(13)), '', array_slice($this->socket_msg, 4));
		
			// Tell the observers
			$this->notify();
		
		}
		
		// Recursive call
		$this->parse();
		
	}
	
	
	
	// <---- public functions ---->
	
	/**
	 * Sends a message to the designated channel or user
	 */
	public function msg($user, $message) {
		
		$message = str_split($message, 450);
		
		for($i = 0; $i < count($message); $i += 1) {
			
			fputs($this->socket, 'PRIVMSG ' . $user . ' :' . $message[$i] . "\r\n");
			echo '<b>PRIVMSG ' . $user . ' :' . $message[$i] . '</b><br>';
			
		}
		
		
		
	}
	
	/**
	 * Sends a notice to the designated user
	 */
	public function notice($user, $message) {
		
		$message = str_split($message, 450);
		
		for($i = 0; $i < count($message); $i += 1) {
			
			fputs($this->socket, 'NOTICE ' . $user . ' :' . $message[$i] . "\r\n");
			echo '<b>NOTICE ' . $user . ' :' . $message[$i] . '</b><br>';
			
		}
		
	}
	
	/**
	 * Joins the designated channel
	 */
	public function join($channel) {
		
		fputs($this->socket, 'JOIN ' . $channel . "\r\n");
		echo '<b>JOIN ' . $channel . '</b><br>';
		
	}
	
	/**
	 * Parts the designated channel
	 */
	public function part($channel) {
		
		fputs($this->socket, 'PART ' . $channel . "\r\n");
		echo '<b>PART ' . $channel . '</b><br>';
		
	}
	
	/**
	 * Quits IRC
	 */
	public function quit($reason) {
		
		if($reason != null) {
			
			fputs($this->socket, 'QUIT :' . $reason . "\r\n");
			echo '<b>QUIT :' . $reason . '</b><br>';
			
		}
		
		else {
			
			fputs($this->socket, "QUIT :\r\n");
			echo '<b>QUIT :</b><br>';			
			
		}
		
	}
	
	/**
	 * Attach a Observer to the Observable Object
	 * @parameter Observer $observer
	 */
	public function attach(Observer $observer) {
		
		$this->observers[] = $observer;
		
	}
	
	/**
	 * Detach the Observer from the Observable Object
	 * @parameter Observer $observer
	 */
	public function detach(Observer $observer) {
		
		for($i = 0; $i < count($this->observers); $i += 1) {
			
			if($this->observers[$i] == $observer) {
				
				unset($this->observers[$i]);
				
			}
			
		}
		
		// Reorder them
		$this->observers = array_values($this->observers);
		
	}
	
	/**
	 * Notify all the the Observers by the Observers' update functions
	 */
	public function notify() {
		
		for($i = 0; $i < count($this->observers); $i += 1) {
			
			$this->observers[$i]->update($this);
			
		}
		
	}
	
}
?>