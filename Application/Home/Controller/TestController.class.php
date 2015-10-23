<?php
namespace Home\Controller;
use Think\Controller;
use Admin\Controller\IndexController;
$cdebug = true;

class TestController extends Controller{
    
    public function index(){
        
        $obj = new IndexController();
        
    }
    
}