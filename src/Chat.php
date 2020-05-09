<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
   protected $clients;

   public function __construct()
   {
      $this->clients = new \SplObjectStorage;
   }

   public function onOpen(ConnectionInterface $conn)
   {
      echo "<pre>";
      var_dump($conn);
      die;
      $this->clients->attach($conn);
   }

   public function onMessage(ConnectionInterface $from, $resultado = [])
   {
      $parametros = json_decode($resultado);
      foreach ($this->clients as $client) {
         $client->send(json_encode([
            "user_id" => $parametros->from,
            "employer_id" => $parametros->from,
            "subject" => $parametros->subject,
            "message" => $parametros->message
         ]));
      }
   }

   public function onClose(ConnectionInterface $conn)
   {
      $this->clients->detach($conn);
      echo "Connection {$conn->resourceId} has disconnected\n";
   }

   public function onError(ConnectionInterface $conn, \Exception $e)
   {
      echo "An error has occurred: {$e->getMessage()}\n";
      $conn->close();
   }
}
