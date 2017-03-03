<?php
class IndexController
{
    function IndexAction()
    {
        $db = new MYSQLPDO();
        $result = $db->fetchAll("select * from bxg_category");
        var_dump($result);
    }
}
