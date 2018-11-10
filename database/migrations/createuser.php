<?php
    namespace Database\Migrations;
    require_once(__DIR__."/migrateable.php");
    class CreateUser implements Migrateable {
        use Immediate;
        public function __construct() {
        }
        public function up() {
            $cmd = "CREATE TABLE IF NOT EXISTS users (
                id              serial,
                email           text    PRIMARY KEY,
                password        text NOT NULL,
                create_time     timestamp,
                last_time       timestamp,
                actived         boolean DEFAULT(false),
                active_token    text,
                last_ip         cidr DEFAULT('0.0.0.0/32'),
                remembered      boolean DEFAULT(false),
                remember_token  text
            );";
            echo $cmd."\n";
            $this->immediateExec(__CLASS__."up",$cmd);
        }
        public function down() {
            $cmd = "DROP TABLE IF EXISTS users CASCADE";
            $this->immediateExec(__CLASS__."down",$cmd);
        }
    }
    $create = new CreateUser();
    $create->up();
    //$create->down();
?>