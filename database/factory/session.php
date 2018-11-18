<?php
    namespace Database\Factory;
    require_once(__DIR__."/../migrations/migrateable.php");
    use Database\Migrations\Immediate;
    class Session {
        use Immediate;
        public function __construct() {

        }
        public function produce(int $count) {
            $cmd = "
                INSERT INTO singlesession(sessionId,firstId,secondId,create_time,message,last_message) VALUES
                (1,82,83,now()::timestamp,array['{\"sessionId\":1,\"firstId\":82,\"secondId\":83,\"ownerId\":82,\"createTime\":\"2011-11-11 11:11:11\",\"content\":\"Hello,Peer!\"}'::json]::json[],'{\"sessionId\":1,\"firstId\":82,\"secondId\":83,\"ownerId\":82,\"createTime\":\"2011-11-11 11:11:11\",\"content\":\"Hello,Peer!\"}'::json)
            ";
            $this->immediateExec($cmd,$cmd);
        }
        public function __destruct() {

        }
    }
    $session = new Session();
    $session->produce(0);
?>