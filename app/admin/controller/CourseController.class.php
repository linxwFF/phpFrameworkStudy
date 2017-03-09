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

    public function DeleteAction($id)
    {

    }

    public function EditAction()
    {
        $this->id = Support::Input('id', 'get', 'id');
        $Course = Support::Dtable('course');
        if(IS_POST){
            $data = [
                'title' => Support::Input('title','post','html'),       //标题
                'category_id' => Support::Input('category_id', 'post', 'id'),    //栏目
                'price' => Support::Input('price', 'post','float'),
                'publish' => Support::Input('save','post','bool') ? 0 : 1,
                'content' => Support::Input('content', 'post', 'string'),
            ];

            //富文本管理
            $data['content'] = $this->HTMLPurifier($data['content']);
            //查处原来的封面图
            $data['thumb'] = $Course->select('thumb', ['id' => $this->id], 'fetchColumn');

            try{
                //处理上传封面
                $data['thumb'] = $this->_uploadThumb($data['thumb']);
            }catch(Exception $e){
                $this->tips(false, $e->getMessage());
                $this->data = $data;
                $this->display();
            }

            if($this->id){
                //修改数据
                $data['id'] = $this->id;
                $Course->save($data);
                $this->tips(true, '修改成功。<a href="/?m=admin&c=course">返回列表</a>');
            }else{
                //添加数据
                $data['time'] = date('Y-m-d H:i:s');
                $add_id = $Course->add($data);
                $this->tips(true, "添加成功。<a href='/?m=admin&c=course&a=edit&id={$add_id}>立即修改</a><a href='/?c=admin&c=course'>返回列表</a>");
            }
        }
        $this->data = $Course->getById($this->id)[0];  //修改后的课程详细数据
        $this->category = Support::Dtable('category')->getList('pid');  //栏目下拉框数据
        $this->display();
    }

    //富文本过滤
    public function HTMLPurifier($html)
    {
        static $Purifier = null;
        if(!$Purifier){
            require EXTEND.'htmlpurifier'.DS.'HTMLPurifier.standalone.php';
            $Purifier = new HTMLPurifier();
        }
        return $Purifier->purify($html);
    }

    //上传封面图并生成缩略图
	private function _uploadThumb($thumb){
		//如果没有上传封面，直接返回
		if(!isset($_FILES['thumb']) || $_FILES['thumb']['error'] == 4){
			return $thumb;
		}
		Support::check_upload($_FILES['thumb']);			//判断上传是否有错误
		$result = Image::thumb(					//为上传文件生成缩略图并保存
			Image::FILL,						//等比例填白
			$_FILES['thumb']['tmp_name'],		//原图路径
			280,								//目标宽度
			156,								//目标高度
			['base_path' => './public/upload/']	//上传目录
		);
		Support::del_file("./public/upload/$thumb");		//删除原图
		return $result;							//返回新图文件路径
	}

	private function _deleteData(){
		$id = I('id', 'get', 'id');
        $Course = Support::Dtable('course');
		//先删除封面图
		$thumb = $Course->select('thumb', ['id'=>$id], 'fetchColumn');
		Support::del_file("./public/upload/$thumb");
		//删除课程
		$Course->delete(['id'=>$id]);
		$this->tips(true, '删除成功。');
	}
}
