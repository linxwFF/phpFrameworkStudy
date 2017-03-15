<?php
//前台栏目模型
class CategoryModel extends Model{
	//栏目列表
	public function getList($mode='all'){
		static $result = [];  //缓存查询结果
		//当第一次调用函数时，到数据库中获取数据
		if(empty($result)){
			//定义数组用于保存结果
			$result = ['id'=>[], 'pid'=>[[]]];
			//到数据库中取出所有的栏目数据
			$data = $this->fetchAll("SELECT id,name,pid,sort FROM $this->table ORDER BY pid ASC, sort ASC ");
			//整理数组格式，方便查找
			foreach($data as $v){
				//基于ID索引
				$result['id'][$v['id']] = $v;
				//基于PID索引
				$result['pid'][$v['pid']][$v['id']] = $v;
			}
		}
		return isset($result[$mode]) ? $result[$mode] : $result;
	}
	//根据PID查找栏目
	public function getByPid($pid){
		$data = $this->getList('pid');
		return isset($data[$pid]) ? $data[$pid] : [];
	}
	//根据ID查找栏目名称
	public function getNameById($id){
		$data = $this->getList('id');
		return isset($data[$id]) ? $data[$id]['name'] : '全部栏目';
	}
	//根据ID查找子栏目
	public function getSubById($id){
		$data = $this->getList('pid');
		$sub = isset($data[$id]) ? array_keys($data[$id]) : [];
		array_unshift($sub, $id); //将$id放入数组开头
		return implode(',', $sub);
	}
	//获取栏目的完整层级路径
	public function getCrumbsById($id){
		$data = $this->getList('id');
		return isset($data[$id]) ? (isset($data[$data[$id]['pid']]) ? [$data[$data[$id]['pid']], $data[$id]] : [$data[$id]]) : [];
	}
}
