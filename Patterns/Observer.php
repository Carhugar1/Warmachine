<?php

/**
 * @author Carhugar1
 */
 

// Observer
abstract class Observer {
	
	/**
	 * Updates the Observer in some way, this is what gets called by the Observable object
	 */
	public abstract function update(Observable $observable);
	
}


// Subject
abstract class Observable {
	
	/**
	 * Attach a Observer to the Observable Object
	 * @parameter Observer $observer
	 */
	public abstract function attach(Observer $observer);
	
	/**
	 * Detach the Observer from the Observable Object
	 * @parameter Observer $observer
	 */
	public abstract function detach(Observer $observer);
	
	/**
	 * Notify all the the Observers by the Observers' update functions
	 */
	public abstract function notify();
	
}
 
?>