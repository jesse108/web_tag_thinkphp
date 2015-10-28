<?php
namespace Home\Controller;
use Think\Controller;

class DemoController extends Controller{
    
    public function index(){
        $condition = array(
            'id' => 1,
        );
        $datas = D('test')->where($condition)->select();
        dump($datas);
    }
    
}