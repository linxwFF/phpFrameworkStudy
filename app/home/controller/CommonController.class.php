<?php
class CommonController extends Controller
{
    public function __construct()
    {
        // parent::__construct();
        //主布局
        $this->layout(COMON_VIEW.'layout.html');
        //检查登录
        $this->_checkLogin();
        //导航
        $this->_nav();
    }

    //获取导航栏栏目
	private function _nav(){
		$this->nav = Support::Dtable('category')->getByPid(0);
	}

    //检查用户登录
	private function _checkLogin(){
		//判断session中是否有用户名信息
		define('IS_LOGIN', Support::Session('user', '', 'isset'));
		//如果登录，则取出Session
		if(IS_LOGIN){
			$this->user = Support::Session('user');
		}
	}

    //生成头像URL
	protected function _avatrURL($avatar){
		return $avatar ? "/public/avatar/$avatar" : '/public/home/image/noavatar.gif';
	}
}
