<?php
    namespace App\ChatStation;
    require_once(__DIR__."/../../vendor/database/postgressql/sqlquery.php");
    use Vendor\Database\Postgressql\SqlQuery;
    class ChatConnection {
        //每个connection维护用户所关心sessionId表
        private $userId_;
        private $singleSessionIds_;
        public function __construct($userId) {
            $this->userId_ = $userId;
            $this->singleSessionIds_ = [];
            if (!$this->initSingleSessionIds()) {
                throw new \Exception("init single session failed");
            }
        }

        public function initSingleSessionIds() {
            $q = "SELECT getSingleSessionsId($this->userId_)";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            if ($result['success']) {
                $data = $result['data'];
                foreach ($data as $k => $v) {
                    $this->singleSessionIds_[$k] = $v['getsinglesessionsid'];
                }
                return true;
            }
            return false;
        }

        public function __destruct() {
            echo "我被释放了!";
        }

        public function haveCareSession($sessionId) {
            foreach ($this->singleSessionIds_ as $k => $v) {
                if ($v == $sessionId) {
                    return true;
                }
            }
            return false;
        }
    }
?>