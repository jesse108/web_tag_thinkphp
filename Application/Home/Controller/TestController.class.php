<?php
namespace Home\Controller;
use Think\Controller;
class TestController extends Controller{
    
    public function index(){
        return array('a'=> 1);
    }
    
}