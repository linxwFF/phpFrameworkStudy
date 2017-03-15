<?php
//前台课程模型
class CourseModel extends Model{
	//获取课程列表
	public function getList($cid, $order, $page, $size){
		//拼接ORDER条件
		$order_arr = [
			'price-asc' => '`price` ASC',
			'price-desc' => '`price` DESC',
			'buy-desc' => '`buy` DESC'
		];
		$sql_order = ' ORDER BY ';
		$sql_order .= isset($order_arr[$order]) ? $order_arr[$order] : '`id` DESC';
		//拼接WHERE条件
		$cids = Support::Dtable('category')->getSubById($cid);
		$sql_where = " WHERE `category_id` IN ($cids) AND `show`='yes'";
		//拼接LIMIT条件
		$sql_limit = ' LIMIT '.$this->getLimit($page, $size);
		return [
			'total' => $this->fetchColumn("SELECT COUNT(*) FROM $this->table $sql_where"),
			'data' => $this->fetchAll("SELECT `id`,`title`,`price`,`thumb`,`buy` FROM $this->table $sql_where $sql_order $sql_limit")
		];
	}
	//获取首页数据
	public function getByCategoryIds($cids, $limit=false){
		$sql_limit = $limit ? " LIMIT $limit " : '';
		return $this->fetchAll("SELECT `id`,`title`,`price`,`thumb`,`buy` FROM $this->table WHERE `category_id` IN ($cids) AND `publish`='1' $sql_limit");
	}
	//最新课程
	public function getNewList($limit=false){
		$sql_limit = $limit ? " LIMIT $limit " : '';
		return $this->fetchAll("SELECT `id`,`title`,`price`,`thumb`,`buy` FROM $this->table WHERE `publish`='1' ORDER BY `id` DESC $sql_limit");
	}
	//热销课程
	public function getRecomment($limit=false){
		$sql_limit = $limit ? " LIMIT $limit " : '';
		return $this->fetchAll("SELECT a.`id`,a.`title`,a.`thumb`,c.`name` AS `category_name` FROM $this->table AS a LEFT JOIN ".Support::Dtable("category")->table." AS c ON a.`category_id`=c.`id` WHERE `show`='yes' ORDER BY a.`buy` DESC $sql_limit");
	}
}
