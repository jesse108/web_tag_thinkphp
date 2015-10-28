CREATE TABLE IF NOT EXISTS `admin` (
  `id` BIGINT(20) PRIMARY KEY NOT NULL  AUTO_INCREMENT,
  `username` VARCHAR(60),
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(64),
  `status` tinyint(2) not null default 1 comment '״̬ 1:����,2:ɾ��',
  `phone` VARCHAR(32),
  `create_time` INT(10) NOT NULL DEFAULT 0,
  `login_time`  int(10) not null default 0 comment '����¼ʱ��',
  `login_ip` varchar(64) comment '����¼IP',
   key(`username`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT ='����Ա��';

CREATE TABLE IF NOT EXISTS `web_site`(
 `id` BIGINT(20) PRIMARY KEY NOT NULL  AUTO_INCREMENT,
 `title` varchar(32) not null comment '����',
 `url` varchar(128) not null comment '������ַ',
 `status` tinyint(2) not null default 1 comment '״̬ 1:����,2:ɾ��',
 `comment` varchar(256) comment '�򵥱�ע',
 `category_id` bigint(20) not null default 0 comment '����ID',
 key(`title`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT ='�����ǩ';

create table if not exists `category`(
 `id` bigint(20) primary key not null auto_increment,
 `title` varchar(32) not null comment  '����',
 `status` tinyint(2) not null default 1 comment '״̬  1:���� 2:ɾ��',
 `level` tinyint(2) not null default 1 comment '�㼶',
 `parent_id` bigint(20) not null default 0 comment  '��ID',
 `type` tinyint(2) not null default 1 comment '���� ǰ��ֻ��1',
 key(`parent_id`)
)ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT ='����';










