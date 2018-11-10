<?php
    namespace Database\Migrations;
    require_once(__DIR__."/migrateable.php");
    use Database\Migrations\Migratable;
    use Database\Migrations\Immediate;
    
    class CreateUserInfo implements Migrateable {
        use Immediate;
        public function __construct() {

        }
        public function up() {
            $this->createHeader();
            $cmd = "
                CREATE TABLE IF NOT EXISTS userinfo (
                    id          serial,
                    email       text    PRIMARY KEY,
                    header      Header,
                    name        varchar(40) DEFAULT('新用户'),
                    sex         varchar(10) DEFAULT('暂不透露'),
                    age         integer,
                    sign        varchar(100),
                    birth       date,
                    vocation    varchar(20),
                    company     varchar(50),
                    zone        varchar(60),
                    hometown    varchar(60)
                );
            ";
            $this->immediateExec("createuserinfo",$cmd);          
            $this->keepWithUsers();
            $this->insertUsersTrigger();
        }
        public function createHeader() {
            $cmd = "
                CREATE TYPE Header AS (
                    src         bytea,
                    suffix      varchar(20)
                );
            ";
            $this->immediateExec("typeheader",$cmd);
        }

        public function keepWithUsers() {
            $cmd = "
                CREATE OR REPLACE FUNCTION keepWithUsers()
                RETURNS TRIGGER AS $$
                    BEGIN
                        INSERT INTO userinfo(email) VALUES (new.email);
                        RETURN NEW;
                    END;
                $$ LANGUAGE plpgsql;
            ";
            $this->immediateExec("keepWithusers",$cmd);
        }
        public function insertUsersTrigger() {
            $cmd = "
                CREATE TRIGGER insertuserstrigger AFTER INSERT on users
                FOR EACH ROW EXECUTE PROCEDURE keepWithUsers();
            ";
            $this->immediateExec("insertuserstrigger",$cmd);
        }
        public function down() {

        }
    }
    $e = new \Database\Migrations\CreateUserInfo();
    $e->up();
?>