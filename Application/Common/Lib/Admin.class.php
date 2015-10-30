<?php
namespace Common\Lib;
use Common\Config\AdminConfig;
use Common\Lib\System;
use Admin\Validate\AdminValidate;

class Admin{
    public static $tableName = 'admin';
    public static $loginExpire = 30; //免登陆记录时长  单位:天
    public static $loginUser;
    
    public static function Login($username,$password,$remember = true){
        if(!$username){
            System::AddError("用户名不得为空");
            return false;
        }
        
        if(!$password){
            System::AddError("密码不得为空");
            return false;
        }
        
        $condition = array(
            'username' => $username,
            'status' => AdminConfig::STATUS_NORMAL,
        );
        $model = D(self::$tableName);
        $user = $model->where($condition)->find();
        $encodePassword = self::EncodePassword($password);
        
        if(!$user){
            if(false){
                $data = array(
                    'username' => $username,
                    'password' => $encodePassword,
                    'create_time' => time(),
                    'status' => AdminConfig::STATUS_NORMAL,
                );
                $model->save($data);
                return false;
            } else {
                System::AddError("找不到这个用户");
                return false;
            }
        }
        
        
        if($user['password'] != $encodePassword){
            System::AddError("密码错误!");
            return false;
        }
        self::SaveLoginUser($user,$remember);
        
        $update = array(
            'login_time' => time(),
            'login_ip' => get_client_ip(),
        );
        $model->where(array('id' => $user['id']))->data($update)->save();
        return $user;
    }
    
    public static function NeedLogin($redirectURL = '/'){
        $loginUser = self::GetLoginUser();
        
        if(!$loginUser && $redirectURL){
            System::AddError("请先登录~");
            redirect($redirectURL);
        }
        
        return $loginUser;
    }
    
    public static function Register($username,$password){
        if(!AdminValidate::CheckRegister($username, $password)){
            return false;
        }
        $password = self::EncodePassword($password);
        $data = array(
            'username' => $username,
            'password' => $password,
            'create_time' => time(),
            'status' => AdminConfig::STATUS_NORMAL,
        );
        $id = D('admin')->add($data);
        if(!$id){
            $error = D('admin')->getError();
            System::AddError( '注册失败' . $error);
            return false;
        }
        return $id;
    }
    
    public static function SaveLoginUser($user,$remember = true){
        self::$loginUser = $user;
        $sessionKey = SESSION_LOGIN_ADMIN;
        session($sessionKey,$user);
        $cookieID = COOKIE_LOGIN_ADMIN_ID;
        $cookieKey = COOKIE_LOGIN_ADMIN_KEY;
        
        if($remember){
            $expire = self::$loginExpire * 86400;
            cookie($cookieID,$user['id'],$expire);
            $cookieEncode = self::EncodeCookieKey($user);
            cookie($cookieKey,$cookieEncode,$expire);
        }
    }
    
    public static function GetLoginUser(){
        if(self::$loginUser){
            return self::$loginUser;
        }
        
        $sessionKey = SESSION_LOGIN_ADMIN;
        $cookieID = COOKIE_LOGIN_ADMIN_ID;
        $cookieKey = COOKIE_LOGIN_ADMIN_KEY;
        $loginUser = session($sessionKey);
        
        if($loginUser){
            self::$loginUser = $loginUser;
            return $loginUser;
        }
        
        $userID = cookie($cookieID);
        $cookieKey = cookie($cookieKey);
        if(!$userID || !$cookieKey){
            return false;
        }
        
        $loginUser = D('admin')->find($userID);
        if(!$loginUser || $loginUser != AdminConfig::STATUS_NORMAL){
            return false;
        }
        
        if(self::EncodeCookieKey($loginUser) != $cookieKey){
            return false;
        }
        
        self::$loginUser = $loginUser;
        session($sessionKey,$loginUser);
        
        return $loginUser;
    }
    

    public static function EncodePassword($password){
        $key = AdminConfig::$pwdKey;
        $password = md5($password) . $key;
        $encode = md5($password);
        return $encode;
    }
    
    public static function EncodeCookieKey($user){
        $userID = $user['id'];
        $passwd = $user['password'];
        $username = $user['username'];
        $code = md5($userID . $passwd . $username);
        return $code;
    }
    
}