<?php
class CategoryModel extends Model
{
    public function getList($mode = 'all')
    {
        static $result = [];    //缓存查询结果
        if(empty($result)){
            $result = ['id' => [], 'pid' => [] ];
            $sql = "select id,name,pid,sort from $this->table order by pid asc, sort asc";
            $data = $this->fetchAll($sql);

            foreach($data as $v){
                $result['id'][$v['id']] = $v;   //基于ID索引
                $result['pid'][$v['pid']][$v['id']] = $v;   //基于PID索引
            }
        }
        return isset($result[$mode]) ? $result[$mode] : $result;
    }

    //获取栏目下的子栏目
    public function getSubById($id)
    {
        $data = $this->getList('pid');
        $sub = isset( $data[$id]) ? array_keys($data[$id]) :[];
        array_unshift($sub, $id);   //将ID放入数组开头
        return implode(',', $sub);
    }
}
