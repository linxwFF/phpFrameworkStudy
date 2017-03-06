<?php
class LoginController extends Controller
{
    public function IndexAction()
    {
        if(IS_POST){
            $name = Support::Input('name', 'post', 'string');
            $password = Support::Input('password', 'post', 'string');
            $captcha = Support::Input('captcha', 'post', 'string');    //验证码机制待开发

            if(!$this->_checkCaptcha($captcha)) //验证验证码
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
            $this->redirect("/?m=admin");
        }else{
            $this->display();
        }
    }

    //创建验证码
    public function create_captchaAction()
    {
        $captcha = new Captcha();
        $code = $captcha->code();   //生成验证码code
        Support::Session('captcha',$code,'save'); //保存验证码CODE到session中
        $captcha->show($code);
    }

    //验证验证码
    private function _checkCaptcha($code)
    {
        $captcha = Support::Session('captcha','get');   //获取session中的验证码
        if(!empty($captcha))
        {
            Support::Session('captcha','unset');//删除session中的验证码code
            return strtoupper($captcha) == strtoupper($code);   //对比
        }
        return false;
    }


    //退出登录
    public function logoutAction()
    {
        Support::Session('admin', '', 'unset');
        $this->redirect("/?m=admin&c=login&a=index");
    }
}
