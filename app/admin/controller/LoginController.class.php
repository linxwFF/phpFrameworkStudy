<?php
class LoginController extends Controller
{
    public function IndexAction()
    {
        if(IS_POST){
            $name = Support::Input('name', 'post', 'string');
            $password = Support::Input('password', 'post', 'string');
            $captcha = true;    //验证码机制待开发
            if(!$captcha)
            {
                $this->tips(false, '验证码错误');
                $this->display();
            }

            //实现用户登录
            try{
                $userinfo = Support::Dtable('admin')->login($name, $password);
                var_dump($userinfo);
            }catch(Exception $e){
                $this->tips(false, $e->getMessage());
                $this->display();
            }
            Support::session('admin', $userinfo, 'save');   //保存Session中
            var_dump($_SESSION);
            echo "登录成功/跳转后台主页面/待开发";
        }else{
            $this->display();
        }
    }
}
