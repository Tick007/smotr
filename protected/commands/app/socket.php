<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Socket implements MessageComponentInterface {
    
    var $wscommand;

    public function __construct($wscommand)
    {
        $this->clients = new \SplObjectStorage;
        $this->wscommand = $wscommand;
        
    }

    public function onOpen(ConnectionInterface $conn) {

        // Store the new connection in $this->clients
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
        
        $this->notifyWS();
        
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        
        //print_r($from);
        //echo "\r\n";
        //echo $msg."\r\n";
        
        
/*
        foreach ( $this->clients as $client ) {

            if ( $from->resourceId == $client->resourceId ) {
                continue;
            }

            $client->send( "Client $from->resourceId said $msg" );
            
        }
        */
        
        $this->wscommand->onReceivecallback($from, $msg, $this->clients);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->notifyWS();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $this->notifyWS();
    }
    
    public function notifyWS(){
        if(isset($this->wscommand) &&  $this->wscommand!=null){
            $this->wscommand->onConnectionListChange($this->clients);
        }
    }
    
}