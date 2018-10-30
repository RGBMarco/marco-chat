<?php
   namespace Vendor\Database\Postgressql;
   require_once(__DIR__."/connection.php");
   class Factory {
       static $connection;
       public function __construct() {
            self::$connection = new Connection();
       }
   }
?>