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
        function getUserInfoAllById(int $id):array {
            $q = "SELECT * FROM getUserInfoAllById($id)";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            $ret = [];
            if ($result['success']) {
                if (isset($result['data'][0])) {
                    return $result['data'][0];
                }
                return $ret;
            } else {
                //need to do more?
                return $ret;
            }
        }
        function isValidName(string $name) {
            return strlen($name) <= 30 ? true : false;
        }
        function isValidSign(string $sign) {
            return strlen($sign) <= 80 ? true:false; 
        }
        function isValidSex(string $sex) {
            switch ($sex) {
                case "男":
                    return true;
                case "女":
                    return true;
                case "暂不透露":
                    return true;
                default:
                    return false;
            }
            return false;
        }
        function isValidBirth(string $birth) {
            $regex = "#([12][0-9]{3})-(([0][1-9])||([1][012]))-([012][0-9])#";
            $isDate = preg_match($regex,$birth);
            if (!$isDate) {
                if ($birth === "未填写") {
                    return true;
                }
            }
            return (bool)$isDate;
        }
        function isValidVocation(string $vocation) {
            return strlen($vocation) <= 18 ? true : false;
        }
        function isValidCompany(string $company) {
            return strlen($company) <= 40 ? true : false;
        }
        function isValidZone(string $zone) {
            return strlen($zone) <= 50 ? true : false;
        }
        function isValidHometown(string $hometown) {
            return strlen($hometown) <= 30 ? true : false;
        }
        function isValidSchool(string $school) {
            return strlen($school) <= 20 ? true : false;
        }
        function isValidPhoto(string &$photo,bool $haveAbout,$photoAbout) {
            if ($haveAbout) {
                $regex = "#(image/png)|(image/jpeg)|(image/jpg)|(image/gif)#";
                if (!isset($photoAbout->type) || !isset($photoAbout->size)) {
                    return false;
                }
                $match = preg_match($regex,$photoAbout->type);
                if (!$match) {
                    return false;
                }
                $maxsize = 4 * 1024 * 1024;
                $size = $photoAbout->size;
                if ($size > $maxsize) {
                    return false;
                }
                return true;
            }
            return true;
        }
        public function updateUserInfo(int $id,$data) {
            $q = "SELECT updateUserInfo($id,'$data->name','$data->sign','$data->sex','$data->birth','$data->vocation','$data->company','$data->zone','$data->hometown','$data->school','$data->havePhoto','{$data->photo}','$data->photoType','$data->isRealDate')";
            //echo $q;
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            if ($result['success']) {
                if ($result['data'][0]['updateuserinfo'] == 't') {
                    return true;
                }
                return false;
            }
            return false;
        }
        public function queryUserInfoHeader(int $id) {
            $q = "SELECT queryUserInfoHeader($id)";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            print_r($result);
            if ($result['success']) {
                if ($result['data'][0]['queryuserinfoheader'] == 't') {
                    return true;
                }
                return false;
            }
            return false;
        }
        public function getUserInfoHeaderById(int $id) {
            $q = "SELECT * FROM getUserinfoHeaderById($id) AS (src bytea,type character varying(20))";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            //var_dump($result);
            if ($result['success']) {
                return $result['data'][0];
            }
            return null;
        }
    }
?>