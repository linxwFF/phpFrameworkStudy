<?php
class VideoController extends CommonController
{
    //验证ID
    private function validateID()
    {
        $this->id = Support::Input("id","get",'id');
        if($this->id == 0){
            $this->tips(false, '请先保存课程信息');
            $this->display();
        }
    }

    //编辑课程视频
    public function EditAction()
    {
        $this->validateID();    //验证ID
        if(IS_POST){
            $this->_saveData($this->id);
            $this->_addData($this->id);
            $this->_deleteData($this->id);
            $this->tips(true, '保存完成');
        }
        $this->data = Support::Dtable('video')->select('id,sort,title,url,trial',['course_id' => $this->id]);
        $this->display();
    }

    //添加课程视频
    private function _addData($course_id)
    {
        $result = [];
        $data = Support::Input('add','post','array');
        foreach($data as $v){
            $result[] = [
                'trial' => Support::Input('trial',$v,'bool') ? 'yes' : 'no',
                'title' => Support::Input('title',$v,'html'),
                'sort' => Support::Input('sort',$v,'int'),
                'url' => Support::Input('url',$v,'html'),
                'course_id' => $course_id,
            ];
        }
        empty($result) || Support::Dtable('video')->add($result);
    }

    //修改视频
    private function _saveData($course_id)
    {
        $result = [];
        $data = Support::Input('save','post','array');
        foreach($data as $k => $v){
            $result[] = [
                'trial' => Support::Input('trial',$v,'bool') ? 'yes' : 'no',
                'title' => Support::Input('title',$v,'html'),
                'sort' => Support::Input('sort',$v,'int'),
                'url' => Support::Input('url',$v,'html'),
                'id' => Support::Input(null,null,'id',$k),
                'course_id' => $course_id,
            ];
        }
        empty($result) || Support::Dtable('video')->save($result,'id,course_id');
    }

    //删除
    private function _deleteData($course_id)
    {
        $result = [];
        $data = Support::Input('del','post','array');
        foreach($data as $v)
        {
            $result[] = [
                'id' => Support::Input(null,null,'id',$v),
                'course_id' => $course_id,
            ];
        }
        empty($result) || Support::Dtable('video')->delete($result);
    }
}
