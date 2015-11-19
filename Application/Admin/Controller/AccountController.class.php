<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Lib\Admin;
use Common\Lib\System;

class AccountController extends Controller{
    
    public function login(){
        
        if($_POST){
            $username = I('post.username');
            $password = I('post.password');
            $remember = I('post.remember');
            $remember = $remember ? true : false;
            
            $user = Admin::Login($username, $password,$remember);
            if($user){
                System::AddError("登陆成功");
                redirect("/");
            }
        }
        
        $this->assign('title','登陆');
        $this->display();
    }
    
    public function logout(){
        Admin::Logout();
        $url = U('Admin/account/login',null,false);
        dump($url);
        exit;
        redirect($url);
    }
    
}