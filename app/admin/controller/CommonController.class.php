<?php
//后台公共控制器
class CommonController extends Controller
{
    protected $user = '';

    public function __construct()
    {
        //parent::__construct();
        $this->_checkLogin();
    }

    private function _checkLogin()
    {
        //判断session中是否有管理信息
        if(Support::Session('admin','','isset'))
        {
            $this->user = Support::Session('admin');
        }else{
            $this->redirect("/?m=admin&c=login");
        }
    }
}
