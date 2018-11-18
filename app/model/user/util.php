<?php
    namespace App\Model\User\Util;
    require_once(__DIR__."/../../../vendor/database/postgressql/sqlquery.php");
    use Vendor\Database\Postgressql\SqlQuery;
    trait UserInfo {
        function getUserNameById($id) {
            $q = "SELECT getUserNameById($id)";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            if ($result['success']) {
                return $result['data'][0]['getusernamebyid'];
            }
            return 'undefined';
        }
    }
?>