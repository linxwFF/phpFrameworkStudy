<?php
class IndexController extends CommonController
{
    public function IndexAction()
    {
        $this->title = '首页';
		//取出前4个栏目中的课程
		$data = [['id' => 0, 'name' => '近期新增课程', 'data' => Support::Dtable('course')->getNewList(4)]];
		//获取前4个顶级栏目的ID
		$category = array_slice(Support::Dtable('category')->getByPid(0), 0, 4);
		foreach($category as $v){
			$cids = Support::Dtable('category')->getSubById($v['id']);
			$data[] = [
				'id' => $v['id'],
				'name' => $v['name'],
				'data' => Support::Dtable('course')->getByCategoryIds($cids, 4)
			];
		}
		$this->data = $data;
		$this->display();
    }
}
