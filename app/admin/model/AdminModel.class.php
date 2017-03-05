<?php
class AdminModel extends Model
{
    //登录的数据库查询核对方法
    public function login($name, $password)
    {
        //根据用户名查询数据
        $result = $this->select('id,name,password', ['name'=> $name],'fetchRow');   //查询一行数据模式

        if(!$result)
        {
            throw new Exception('登录失败，用户名或者密码错误. bu cun zai ci yong hu');
        }else if($result['password'] !== $password)
        {
            throw new Exception('登录失败，用户名获取密码错误');
        }

        return ['name' => $result['name'], 'id' => $result['id']];
    }
}
