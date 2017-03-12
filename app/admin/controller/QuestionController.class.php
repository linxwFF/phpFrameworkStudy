<?php
class QuestionController extends CommonController
{
    public function editAction()
    {
        $this->id = Support::Input('id','get','id');
        $this->display();
    }

    public function addtestAction()
    {

    }
}
