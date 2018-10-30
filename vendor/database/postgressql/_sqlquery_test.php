<?php
    namespace Vendor\Database\Postgressql;
    require_once(__DIR__."/sqlquery.php");
    require_once(__DIR__."/../exceptions/connect.php");
    use Vendor\Database\Exceptions\ConnectException;
    try {
            $sql = new SqlQuery("users");
            print_r($sql->limit(5,['name','email']));
            //var_dump($sql->first(5));
            //var_dump(FALSE);
    }catch(ConnectException $ce) {
        echo $ce->errorMessage();
    }
?>