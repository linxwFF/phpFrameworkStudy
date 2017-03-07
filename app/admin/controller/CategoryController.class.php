<?php
//栏目管理
class CategoryController extends CommonController
{
    public function IndexAction()
    {
        if(IS_POST){
            $this->_saveData();
            $this->_addData();
            $this->tips(true, '保存成功');
        }

        if('del' == Support::Input('exec', 'get', 'string'))
        {
            $this->_delteData();
        }

        $this->data = Support::Dtable('category')->getList('pid');
        $this->display();
    }

    private function _saveData()
    {
        $result = [];
        $save = Support::Input('save','post','array');
        foreach($save as $k=>$v)
        {
            $result[] = [
                'name' => Support::Input('name', $v, 'html'),
                'sort' => Support::Input('sort', $v, 'int'),
                'id' => Support::Input(null,null,'id', $k),
            ];
        }
        if(!empty($result))
        {
            Support::Dtable('category')->save($result, 'id');
        }
    }

    private function _addData()
    {
        $result = [];
        $add = Support::Input('add','post','array');
        foreach($add as $v)
        {
            $result[] = [
                    'pid' => Support::Input('pid', $v, 'id'),
                    'name' => Support::Input('name', $v, 'html'),
                    'sort' => Support::Input('sort', $v, 'int'),
            ];
        }
        if(!empty($result))
        {
            Support::Dtable('category')->add($result);
        }
    }

    //删除栏目
    private function _delteData()
    {
        $id = Support::Input('id', 'get', 'id');
        $model = Support::Dtable('category');
        if($model->exists(['pid' => $id]))
        {
            $this->tips(false, '该栏下有子目录不能删除');
        }else{
            $model->delete(['id' => $id]);
            //$model->change('category_id', $id, 0);
            $this->tips(true, '删除成功');
        }
    }
}
