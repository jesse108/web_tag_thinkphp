<?php
namespace Common\Lib;

/**
 *  系统消息类
 * @author Jesse
 *
 */
class System{
    
    public static function AddError($error){
        $errors = self::GetError(true,false);
        $errors[] = $error;
        self::SetError($errors);
    }
    
    public static function AddNotice($notice){
        $notices = self::GetNotice(true,false);
        $notices[] = $notice;
        self::SetNotice($notices);
    }

    public static function SetError($errors){
        $key = SESSION_SYSTEM_MSG_ERROR;
        session($key,$errors);
    }
    
    public static function GetError($isRaw = false,$once = true){
        $key = SESSION_SYSTEM_MSG_ERROR;
        $errors = session($key);
        if($once){
            session($key,null);
        }
        if($isRaw){
            return $errors;
        }
        return implode(',', $errors);
    }
    
    public static function SetNotice($notices){
        $key = SESSION_SYSTEM_MSG_NORICE;
        session($key,$notices);
    }
    
    public static function GetNotice($isRaw = false,$once = true){
        $key = SESSION_SYSTEM_MSG_NORICE;
        $notices = session($key);
        if($once){
            session($key,null);
        }
        if($isRaw){
            return $notices;
        }
        return implode(',', $notices);
    }
    
    public static function test(){
        echo 'a';
    }
}