<?php
class MYSQLPDO
{
    protected static $db = null;
    
    public function __construct()
    {
        if(!self::$db)
        {
            self::$db = self::__connect();
        }
    }

    private function __clone(){} //防止克隆

    private static function __connect()
    {
        $config = Support::Config('DB');
        $dsn = "{$config['db']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset={$config['charset']}";
        try{
            self::$db = new PDO($dsn, $config['user'],$config['pass']);
        }catch(PDOException $e){
            exit('数据库连接失败：'.$e->getMessage());
        }
    }
}
