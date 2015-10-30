<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Lib\Admin;

class AccountController extends Controller{
    
    public function login(){
        
        if($_POST){
            $username = I('post.username');
            $password = I('post.password');
            $remember = I('post.remember');
            $remember = $remember ? true : false;
            
            $user = Admin::Login($username, $password,$remember);
            
        }
        
        $this->assign('title','登陆');
        $this->display();
    }
    
    public function logout(){
        
    }
    
}