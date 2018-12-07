<?php
    namespace App\Model\Friend\Util;
    require_once(__DIR__."/../../../vendor/database/postgressql/sqlquery.php");
    use Vendor\Database\Postgressql\SqlQuery;
    trait FriendUtil {
        public function getFriendsById($id) {
            $q = "SELECT * FROM getFriendsById($id) AS(id integer,name text,sign text);";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            if ($result['success']) {
                return $result['data'];
            }
            return [];
        }
        public function requestToNewFriend($requestid,$resposeid,$msg) {
            $q = "SELECT requestToNewFriend($requestid,$responseid,'$msg') AS success";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            if ($result['success']) {
                if ($result['data'][0]['success'] == 't') {
                    return true;
                }
            }
            return false;
        }
        public function findFriend(string $e) {
            $q = "SELECT * FROM findFriend('$e') AS(id integer,name text,email text)";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            if ($result['success']) {
                return $result['data'];
            }
            return [];
        }
    }
?>