<?php
namespace Admin\Validate;
use Common\Lib\System;

class AdminValidate{
    
    public static function CheckRegister($username,$password){
        if(!$username){
            System::AddError("用户名不可为空");
            return false;
        }
        
        if(!$password){
            System::AddError("密码不可为空");
            return false;
        }
        
        $user = D('admin')->where(array('username' => $username)) -> field('id') -> find();
        if($user){
            System::AddError("已经有这个名字了,请换个名字");
            return false;
        }
        return true;
    }
}