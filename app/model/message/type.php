<?php
    namespace App\Model\Message;
    require_once(__DIR__."/../user/util.php");
    use App\Model\User\Util\UserInfo;
    class Message_t {
        private $sessionId_;
        private $fromId_;
        private $toId_;
        private $content_;
        private $fromName_;
        private $toName_;
        private $createTime_;
        public function __construct() {
        }
        //判断收取消息格式是否正确
        public function isRightFormat() {

        }
        /*
            {
                sessionId:*,
                fromId:*,
                toId:*,
                content:*,
                fromName:*,
                toName:*,
                createTime:*,
            }
        */
        //补全发送的消息
        public function completeMessage() {

        }
    }
/*

*/

    class Record_t {
        use UserInfo;
        private $sessionId;
        private $firstId;
        private $secondId;
        private $createTime;
        private $content;

        private $firstName;
        private $secondName;
        public function __construct($record) {
            $this->firstName = $this->getUserNameById($record->firstId);
            $this->secondName = $this->getUserNameById($record->secondId);
            $this->sessionId = $record->sessionId;
            $this->firstId = $record->firstId;
            $this->secondId = $record->secondId;
            $this->createTime = $record->createTime;
            $this->content = $record->content;
        }

        public function getArrayFormat():array {
            return [
                'sessionId'     => $this->sessionId,
                'firstId'       => $this->firstId,
                'secondId'      => $this->secondId,
                'firstName'     => $this->firstName,
                'secondName'    => $this->secondName,
                'createTime'    => $this->createTime,
                'content'       => $this->content,
            ];
        }
    }
?>