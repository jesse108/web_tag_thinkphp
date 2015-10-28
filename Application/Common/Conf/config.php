<?php
define('SESSION_SYSTEM_MSG_ERROR', 'session_001');
define('SESSION_SYSTEM_MSG_NORICE', 'session_002');
define('SESSION_LOGIN_ADMIN', 'session_003');


define('COOKIE_LOGIN_ADMIN_ID', 'cookie_001');
define('COOKIE_LOGIN_ADMIN_KEY','cookie_002');

return array(
	//'配置项'=>'配置值'
    'db_type'    =>   'mysql',
    'db_host'    =>   'localhost',
    'db_user'    =>   'root',
    'db_pwd'     =>   '123456',
    'db_port'    =>    3306,
    'db_name'    =>    'web_tag', 
    
    /////////
    'APP_SUB_DOMAIN_DEPLOY' => 1,
    'APP_SUB_DOMAIN_RULES' => array(
        'admin.local.webtag.com' => 'Admin',
        'local.webtag.com' => 'Home',
    ),
);