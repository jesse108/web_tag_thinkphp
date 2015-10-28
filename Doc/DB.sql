CREATE TABLE IF NOT EXISTS `admin` (
  `id` BIGINT(20) PRIMARY KEY NOT NULL  AUTO_INCREMENT,
  `username` VARCHAR(60),
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(64),
  `status` tinyint(2) not null default 1 comment '状态 1:正常,2:删除',
  `phone` VARCHAR(32),
  `create_time` INT(10) NOT NULL DEFAULT 0,
  `login_time`  int(10) not null default 0 comment '最后登录时间',
  `login_ip` varchar(64) comment '最后登录IP',
   key(`username`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT ='管理员表';

CREATE TABLE IF NOT EXISTS `web_site`(
 `id` BIGINT(20) PRIMARY KEY NOT NULL  AUTO_INCREMENT,
 `title` varchar(32) not null comment '标题',
 `url` varchar(128) not null comment '官网网址',
 `status` tinyint(2) not null default 1 comment '状态 1:正常,2:删除',
 `comment` varchar(256) comment '简单备注',
 `category_id` bigint(20) not null default 0 comment '分类ID',
 key(`title`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT ='网络标签';

create table if not exists `category`(
 `id` bigint(20) primary key not null auto_increment,
 `title` varchar(32) not null comment  '标题',
 `status` tinyint(2) not null default 1 comment '状态  1:正常 2:删除',
 `level` tinyint(2) not null default 1 comment '层级',
 `parent_id` bigint(20) not null default 0 comment  '父ID',
 `type` tinyint(2) not null default 1 comment '分类 前期只有1',
 key(`parent_id`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT ='分类';










