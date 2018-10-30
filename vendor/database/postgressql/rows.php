<?php
    namespace Vendor\Database\Postgressql;
    interface Rows {
        function first($column = null);
       // function all($column = null);
        function limit(int $rowCount,$column = null);
        /*function isExist($key,$value);
        function sortBy(array $pred,callable $func);*/
    }
?>