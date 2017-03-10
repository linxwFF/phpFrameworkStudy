<?php
class VideoController extends CommonController
{
    public function IndexAction()
    {
        echo "123";
    }

    public function EditAction()
    {
        $this->id = Support::Input("id","get",'id');
        if(!$this->id){
            $this->tips(false, '请先保存课程信息');
            $this->display();
        }
        $this->data = Support::Dtable('video')->select('id,sort,title,url,trial',['course_id' => $this->id])[0];
        $this->display();
    }
}
