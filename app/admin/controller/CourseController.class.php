<?php
class CourseController extends CommonController
{
    public function IndexAction()
    {
        //获取列表参数
        $this->category_id = Support::Input('category_id', 'get', 'id');    //栏目ID
        $this->page = Support::Input('page', 'get', 'page');    //页码
        $this->size = Support::Input('size', 'get', 'page', 3); //默认值3页
        $this->search = Support::Input('search', 'get', 'html'); //搜索关键字
        $this->order = Support::Input('order', 'get', 'string');    //排序条件

        $this->order_arr = ['time-desc' => '时间降序', 'time-asc' => '时间升序', 'show-desc' => '发布状态'];    //预设排序字段

        //获取课程数据
        $this->data = Support::Dtable('course')->getList(
            $this->category_id,
            $this->order,
            $this->search,
            $this->page,
            $this->size
        );
        //获取栏目数据
        $this->category = Support::Dtable('category')->getList('pid');
        $this->display();
    }
}
