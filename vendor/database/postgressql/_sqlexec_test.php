<?php
    namespace Vendor\Database\Postgressql;
    require_once(__DIR__."/sqlexec.php");
    $sql = new SqlExec();
    $str = "
        CREATE TABLE student
        (
            name    text,
            age     integer
        );
    ";
    echo $str . "\n";
    $success = $sql->sqlPrepare("marco",$str);
    $sql->sqlCanel("marco");
    $sql->sqlExec("marco",[]);
?>