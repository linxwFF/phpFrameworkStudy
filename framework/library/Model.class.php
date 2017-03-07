<?php
class Model extends MYSQLPDO
{
    protected $table = '';  //保存模型表名

    //通过构造方法初始化表名
    public function __construct($table = false)
    {
        parent::__construct();
        $this->table = $table ? Support::Config('DB')['prefix'].$table : '';
    }

    //多字段查询，模式可选
    public function select($fields, $data, $mode='fetchAll')
    {
        $fields = str_replace(',', " , ",$fields);
        $where = implode(' AND ',self::_fieldsMap(array_keys($data)));
        $sql = "select ". $fields ." from ".$this->table." where ".$where;
        return $this->$mode($sql, $data);
    }

    // id = :id
    private static function _fieldsMap($fields)
    {
        //关联数组的占位符 ：
        return array_map(function($v){ return "$v = :$v"; } ,$fields);
    }

    //自动从一维数组或者二维数组获取字段
    private static function _getFields($data)
    {
        return isset($data[0]) ? array_keys($data[0]) : array_keys($data);
    }

    //添加记录
    public function add($data)
    {
        $fields = self::_getFields($data);
        $key  = implode(' , ', $fields);
        $value = implode(' , :', $fields);
        $sql = "insert into ". $this->table . ' ('.$key.') ' . "values (:".$value.")";
        return $this->query($sql, $data) ? $this->lastInsertId() : false;
    }

    //修改数据
    public function save($data, $where = 'id')
    {
        $fields = self::_getFields($data);
        $where = explode(',', $where);  //合并所有where
        foreach($where as $v)
        {
            unset($fields[$v]); //从操作字段中去出where字段
        }
        $fields = implode(',', self::_fieldsMap($fields));
        $where = implode(' AND ', self::_fieldsMap($where));
        $sql = "update ". $this->table ." set ". $fields ." where ".$where;
        return $this->exec($sql, $data);
    }

    //修改单个字段
    public function change($fields, $old, $new)
    {
        $sql = "update $this->table set $fields=:new where $fields=:old";
        return $this->exec($sql,['new' => $new,'old' => $old]);
    }

    //删除记录
    public function delete($data)
    {
        $fields = implode(' AND ', self::_fieldsMap(self::_getFields($data)));
        $sql = "delete from $this->table where $fields";
        return $this->exec($sql,$data);
    }

    //对LIKE条件转义
    public static function escapeLike($like)
    {
        return strtr($like, ['%' => '\%', '_' => '\_', '\\'=>'\\\\']);
    }

    //处理SQL语句的Limit部分
    public static function getLimit($page, $size)
    {
        return ($page-1) * $size .',' .$size;
    }

    //根据条件检测记录是否存在
    public function exists($data)
    {
        $where = implode(' AND ', self::_fieldsMap(self::_getFields($data)));
        $sql = "select 1 from ".$this->table." where ".$where;
        return (bool)$this->fetchColumn($sql, $data);
    }
}
