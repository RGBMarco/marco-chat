<?php
    namespace Database\Migrations;
    require_once(__DIR__."/migrateable.php");
    use Database\Migrations\Migrateable;
    use Database\Migrations\Immediate;

    class SingleSession implements Migrateable {
        use Immediate;
        public function __construct() {

        }
        public function up() {
            $this->create();
        }
        public function create() {
            $cmd = "
                CREATE TABLE singlesession (
                    sessionId       serial,
                    firstid         integer NOT NULL,
                    secondid        integer NOT NULL,
                    create_time     timestamp,
                    message         json[]                  
                );
            ";
            $this->immediateExec($cmd,$cmd);
        }
        public function down() {

        }

        public function __destruct() {

        }
    }
    $s = new \Database\Migrations\SingleSession();
    $s->up();
?>