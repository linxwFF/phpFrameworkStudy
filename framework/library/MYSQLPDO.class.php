<?php
class MYSQLPDO
{
    protected static $db = null;

    public function __construct()
    {
        if(!self::$db)
        {
            self::__connect();
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

    //处理SQL语句
    //预处理SQL语句
    public function query($sql, $data=[])
    {
        $stmt = self::$db->prepare($sql);   //SQL语句预处理
        //批量执行
        if(!is_array(current($data)))
        {
            $data = [$data];    //如果不是二维数组，自动转换成二维数组
        }
        foreach($data as $v)
        {
            if(false === $stmt->execute($v)){   //执行SQL语句
                exit('数据库查询失败：.'.implode('-', $stmt->errorInfo()));
            }
        }
        return $stmt;
    }

    //受影响函数
    public function exec($sql, $data=[])
    {
        return $this->query($sql, $data)->rowCount();
    }

    //所有结果
    public function fetchAll($sql, $data=[])
    {
        return $this->query($sql, $data)->fetchAll(PDO::FETCH_ASSOC);
    }

    //一行结果
    public function fetchRow($sql, $data=[])
    {
        return $this->query($sql, $data)->fetch(PDO::FETCH_ASSOC);
    }

    //取得一列结果
    public function fetchColumn($sql, $data=[])
    {
        return $this->query($sql, $data)->fetchColumn();
    }

    //获取最后插入的ID
    public function lastInsertId()
    {
        return self::$db->lastInsertId();
    }
}
