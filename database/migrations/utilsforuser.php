<?php
    namespace Database\Migrations;
    require_once(__DIR__."/migrateable.php");
    class UtilsForUser implements Migrateable {
        use Immediate;
        public function __construct() {

        }
        public function up() {
            $cmd1 = '
                CREATE  OR REPLACE  FUNCTION isActivedUser (e TEXT)
                RETURNS BOOLEAN AS $$
                    DECLARE exist BOOLEAN;
                    DECLARE temp  integer;
                    BEGIN
                        SELECT count(email) INTO temp FROM users WHERE email = $1 AND actived;
                        IF temp = 0 THEN
                            exist = false;
                        ELSE
                            exist = true;
                        END IF;
                        RETURN exist;
                    END; 
                $$ LANGUAGE plpgsql;
            ';
            $this->immediateExec("userexist",$cmd1);
            $cmd2 = '
                CREATE OR REPLACE FUNCTION signUpUser(e text,pw text,at text)
                RETURNS BOOLEAN AS $$
                    DECLARE temp INTEGER;
                    BEGIN
                        SELECT count(email) INTO temp  WHERE email = $1; 
                        IF CAST(temp AS BOOLEAN) THEN
                            INSERT INTO users(email,password,active_token) VALUES($1,$2,$3);
                        ELSE
                            UPDATE users SET pw = $2,active_token = $3 WHERE email = $1;
                        END IF;
                        RETURN TRUE;
                    END; 
                $$ LANGUAGE plpgsql;
            ';
            $this->immediateExec('signupuser',$cmd2);
            $cmd3 = '
                CREATE OR REPLACE FUNCTION isValidUser(e text,pw text)
                RETURNS BOOLEAN AS $$
                    DECLARE temp integer;
                    BEGIN
                        SELECT count(email) INTO temp FROM users WHERE email = $1 AND password = $2;
                        RETURN CAST(temp AS BOOLEAN);
                    END;
                $$ LANGUAGE plpgsql;
            ';
            $this->immediateExec('isvaliduser',$cmd3);
            $cmd4 = '
                CREATE OR REPLACE FUNCTION activeUser(e text)
                RETURNS BOOLEAN AS $$
                    BEGIN    
                        UPDATE users SET actived = true WHERE email = $1;
                    END;
                $$ LANGUAGE plpgsql;
            ';
            $this->immediateExec('activeUser',$cmd4);
        }
        
        public function down() {
            $cmd1 = "DROP FUNCTION IF EXISTS isActivedUser(text)";
            $this->immediateExec("dropactivedUser",$cmd1);
            $cmd2 = "DROP FUNCTION IF EXISTS signUpUser(text,text,text)";
            $this->immediateExec("dropsignUpUser",$cmd2);
            $cmd3 = "DROP FUNCTION IF EXISTS isValidUser(text,text)";
            $this->immediateExec("dropisvalidUser",$cmd3);
            $cmd4 = "DROP FUNCTION IF EXISTS activeUser(text)";
            $this->immediateExec("dropactiveUser",$cmd4);
        }
    }
    $utils = new \Database\Migrations\UtilsForUser();
    $utils->up();
?>