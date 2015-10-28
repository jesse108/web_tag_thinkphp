<?php
namespace Common\Lib;
use Common\Util\UtilArray;
use Common\Lib\System;

class Category{
    public static $tableName = 'category';
    
    public static function Fetch($ids, $col = null){
        $model = D(self::$tableName);
        $pk = $model->getPk();
        $col = $col ? $col : $pk;
        
        
        if(!is_array($ids)){
            $data = $model->where(array($col => $ids)) -> find();
        } else {
            $condition = array(
                $col => array('in',$ids),
            );
            $data = $model->where($condition)->select();
            $data = $data ? UtilArray::AssColumn($data,$pk) : null;
        }
        return $data;
    }
    
    public static function Create($data){
        $model = D(self::$tableName);
        $id = $model->data($data)->save();
        if(!$id){
            System::AddError($model->getError());
        }
        return $id;
    }
    
    
}

