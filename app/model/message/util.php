<?php
    namespace App\Model\Message\Util;
    require_once(__DIR__."/../../../vendor/database/postgressql/sqlquery.php");
    use Vendor\Database\Postgressql\SqlQuery;

    trait MessageUtil {
        public function updateSingleSessionMessage(int $fi,int $si,string $msg) {
            $q = "SELECT updateSingleSessionMessage($fi,$si,'$msg') AS success";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            if ($result['success']) {
                if ($result['data'][0] == 't') {
                    return true;
                }
            }
            return false;
        }
    }
?>