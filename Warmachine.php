<?php

/*
 * @author Carhugar1
 * This is Warmachine
 */
 
include_once 'IRCBot.php';

//So the bot doesnt stop.
set_time_limit(0);
ini_set('display_errors', 'on');

//Configuration for the bot
$config = array( 
        'server' => 'irc.rizon.net', 
        'port'   => 6667, 
        'channel' => '#Carhugar1',
        'nick'   => 'Warmachine', 
        'pass'   => 'DuckN0#e', 
);

//Start the bot
$bot = new IRCBot($config);

?>