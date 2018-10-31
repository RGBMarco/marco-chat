<?php
    namespace Database\Migrations;
    require_once(__DIR__."/../../vendor/database/postgressql/sqlexec.php");
    use \Vendor\Database\Postgressql\SqlExec;
    interface Migrateable {
        function up();
        function down();
    }
    trait Immediate {
        function immediateExec(string $stmtName,string $cmd) {
            $execable = new SqlExec();
            $execable->sqlPrepare($stmtName,$cmd);
            $execable->sqlExec($stmtName,[]);
        }
    }
?>