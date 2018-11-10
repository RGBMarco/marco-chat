<?php
    namespace Database\Migrations;
    require_once(__DIR__."/migrateable.php");
    class UtilsForUser implements Migrateable {
        use Immediate;
        public function __construct() {

        }
        public function up() {
            $cmd1 = "
                CREATE  OR REPLACE  FUNCTION isActivedUser (e TEXT)
                RETURNS BOOLEAN AS $$
                    DECLARE exist BOOLEAN;
                    DECLARE temp  integer;
                    BEGIN
                        SELECT count(email) INTO temp FROM users WHERE email = $1 AND actived = true;
                        IF CAST(temp AS BOOLEAN) THEN
                            exist = true;
                        ELSE
                            exist = false;
                        END IF;
                        RETURN exist;
                    END; 
                $$ LANGUAGE plpgsql;
            ";
            $this->immediateExec("userexist",$cmd1);
            $cmd2 = '
                CREATE OR REPLACE FUNCTION signUpUser(e text,pw text,at text)
                RETURNS BOOLEAN AS $$
                    DECLARE temp INTEGER;
                    BEGIN
                        SELECT count(email) INTO temp FROM users WHERE email = $1; 
                        IF CAST(temp AS BOOLEAN) THEN
                            UPDATE users SET password = $2,active_token = $3 WHERE email = $1;
                        ELSE
                            INSERT INTO users(email,password,active_token) VALUES($1,$2,$3);
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
                CREATE OR REPLACE FUNCTION activeUser(e text,t text)
                RETURNS BOOLEAN AS $$
                    DECLARE temp INTEGER;
                    BEGIN
                        SELECT count(email) INTO temp FROM users WHERE email = $1 AND active_token = $2 AND actived = false;   
                        IF CAST(temp AS BOOLEAN) THEN
                            RETURN FALSE;
                        END IF;
                        UPDATE users SET actived = true WHERE email = $1 AND active_token = $2;
                        RETURN TRUE;
                    END;
                $$ LANGUAGE plpgsql;
            ';
           $this->immediateExec('activeUser',$cmd4);
           $this->canLogin();
           $this->rememberLogin();
           $this->cancelRememberLogin();
           $this->updateLogin();
           $this->getUserId();
        }
        
        public function canLogin() {
            $cmd = "
                CREATE OR REPLACE FUNCTION canLogin(e text,p text)
                RETURNS BOOLEAN AS $$
                    DECLARE temp BOOLEAN;
                    BEGIN
                        SELECT count(email) INTO temp FROM users WHERE email = $1 AND password = $2 AND actived = true;
                        IF CAST(temp AS BOOLEAN) THEN
                            RETURN true;
                        ELSE
                            RETURN false;
                        END IF;
                    END;
                $$ LANGUAGE plpgsql;
            ";
            $this->immediateExec("upcanlogin",$cmd);
        }
        public function rememberLogin() {
            $cmd = "
                CREATE OR REPLACE FUNCTION rememberLogin(e text,rt text)
                RETURNS void AS $$
                    BEGIN
                        UPDATE users SET remembered = true, remember_token = $2 WHERE email = $1;
                    END;
                $$ LANGUAGE plpgsql;
            ";
            $this->immediateExec("uprememberlogin",$cmd);
        }
        public function updateLogin() {
            $cmd = "
                CREATE OR REPLACE FUNCTION updateLogin(e text,li text)
                RETURNS void AS $$
                    BEGIN
                        UPDATE users SET last_time = now()::timestamp, last_ip = CAST($2 AS caddr) WHERE email = $1;
                    END;
                $$  LANGUAGE plpgsql;
            ";
            $this->immediateExec("upupdatelogin",$cmd);
        }
        public function cancelRememberLogin() {
            $cmd = "
                CREATE OR REPLACE FUNCTION cancelRememberLogin(e text)
                RETURNS void AS $$
                    BEGIN
                        UPDATE users SET remembered = false, remember_token = NULL WHERE email = $1;
                    END;
                $$ LANGUAGE plpgsql;
            ";
            $this->immediateExec("upcancelRememberlogin",$cmd);
        }

        public function getUserId() {
            $cmd = "
                    CREATE OR REPLACE FUNCTION getUserId(e text)
                    RETURNS integer AS $$
                        DECLARE temp INTEGER;
                            BEGIN
                                SELECT id into temp FROM users WHERE email = $1;
                                RETURN temp;
                            END;
                    $$ LANGUAGE plpgsql;
            ";
            $this->immediateExec("getuserid",$cmd);
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