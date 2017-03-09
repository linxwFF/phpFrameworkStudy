<?php
class CourseModel extends Model
{
    public function getList($category_id, $order, $search='', $page, $size)
    {
        $order_arr = ['time-asc'=> ' a.id asc ', 'time-desc' => ' a.id desc', 'show-desc' => 'a.show desc'];
        $sql_order = ' order by ';
        $sql_order .= isset($order_arr[$order]) ? $order_arr[$order] : ' id desc ';
        $sql_where = 'where 1=1';
        $SubByID = Support::Dtable('category')->getSubById($category_id);   //顶级栏目
        $sql_where .= $category_id ? ' AND a.category_id IN ( '. $SubByID .' ) ' : "";

        //拼接Limit条件
        $sql_limit = ' LIMIT '.$this->getLimit($page, $size);
        if(!empty($search)){
            $sql_where .= ' AND a.title like :search ';
            $sql_search = '%'.self::escapeLike($search).'%';
        }else{ $sql_search = ''; }

        $sql_total = 'select count(*) from '. $this->table .' as a '.$sql_where;
        $sql_data  = 'select a.id, a.category_id, a.title, a.publish, a.time, c.name AS category_name from '. $this->table
        .' a left join '. Support::Dtable('category')->table .' as c on a.category_id=c.id '.$sql_where. $sql_order .$sql_limit;

        return [
            //获取记录总数
            'total' => $this->fetchColumn($sql_total,['search' => $sql_search]),
            'data' => $this->fetchAll($sql_data, ['search' => $sql_search]),
        ];
    }

    public function getById($id)
    {
        $sql = "select * from $this->table where id = $id limit 0,1";
        $data = $this->fetchAll($sql);
        return $data;
    }


}
