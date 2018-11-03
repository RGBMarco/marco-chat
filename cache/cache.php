<?php
    namespace Cache;
    require_once(__DIR__."/view/viewConfig.php");
    require_once(__DIR__."/exceptions/cache.php");
    use Cache\View\ViewConfig;
    use Cache\Exceptions\CacheException;
    class Cache {
        private $redis_;
        const VIEWKEY = "view";
        public function __construct() {
            $this->redis_ = new \Redis();
            if (!$this->redis_->connect("127.0.0.1","6379")) {
                throw new CacheException("Redis Connect failed");
            }
            $this->cacheView();
        }
        public function cacheView() {
            $views = ViewConfig::$viewCache;
            foreach ($views as $k => $v) {
                $size = filesize($v);
                if (!$size) {
                    throw new CacheException("cache file $v is not exist or length is 0");
                }
                $handle = fopen($v,"r");
                $str = fread($handle,$size);
                if (!$str) {
                    throw new CacheException("file $v read failed");
                }
                $this->redis_->hset(self::VIEWKEY,$k,$str);
            }
        }
        public function __destruct() {
            if ($this->redis_) {
                $this->redis_->close();
            }
        }
    }
?>