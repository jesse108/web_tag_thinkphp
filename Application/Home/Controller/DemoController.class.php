<?php
namespace Home\Controller;
use Think\Controller;
use Common\Lib\Category;

class DemoController extends Controller{
    
    public function index(){
        $datas = D('test')->getPk();
        $datas = Category::Fetch(array(1,2,3));
        dump($datas);
        $this->display();
    }
    
}