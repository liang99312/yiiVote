CREATE SCHEMA `yiivote` ;

CREATE TABLE `yiivote`.`yii_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(225) NOT NULL,
  `pwd` char(32)  NULL,
  `authKey` char(200)  NULL,
  `accessToken` char(200)  NULL,
  `nickname` varchar(225)  NULL,
  `thumb` varchar(225)  NULL,
  `email` varchar(225)  NULL,
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `yiivote`.`yii_vuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_name` varchar(225) NOT NULL,
  `u_code` varchar(32) NOT NULL,
`u_zhiwu` varchar(255) NOT NULL,
`u_zhiji` varchar(255) NOT NULL,
`d_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

  
  CREATE TABLE `yiivote`.`yii_vrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `u_name` varchar(225) NOT NULL,
  `u_code` varchar(32) NOT NULL,
  `u_dept` varchar(255) NOT NULL,
`d_id` int(11) NOT NULL,
  `d3` int(11) NOT NULL default 0,
  `d4` int(11) NOT NULL default 0,
  `d5` int(11) NOT NULL default 0,
  `d6` int(11) NOT NULL default 0,
  `d7` int(11) NOT NULL default 0,
  `d8` int(11) NOT NULL default 0,
  `d9` int(11) NOT NULL default 0,
  `d10` int(11) NOT NULL default 0,
  `yijian` VARCHAR(1000) NULL,
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
  
  CREATE TABLE `yiivote`.`yii_vresult` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `u_name` varchar(225) NOT NULL,
  `u_code` varchar(32) NOT NULL,
  `u_dept` varchar(255) NOT NULL,
`d_id` int(11) NOT NULL,
  `d3` float NOT NULL default 0,
  `d4` float NOT NULL default 0,
  `d5` float NOT NULL default 0,
  `d6` float NOT NULL default 0,
  `d7` float NOT NULL default 0,
  `d8` float NOT NULL default 0,
  `d9` float NOT NULL default 0,
  `d10` float NOT NULL default 0,
  `zongf` float NOT NULL default 0,
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
  
/*20160505*/
 CREATE TABLE `yiivote`.`yii_vplan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_name` varchar(225) NOT NULL,
  `p_state` varchar(32) NOT NULL,
`p_aflag` varchar(32) NOT NULL,
  `p_date` datetime NOT NULL,
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

 CREATE TABLE `yiivote`.`yii_vdept` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `d_code` varchar(225) NOT NULL,
  `d_name` varchar(225) NOT NULL,
  `d_type` varchar(225) NOT NULL,
  `d_remark` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
  
  CREATE TABLE `yiivote`.`yii_vplandept` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `d_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `yiivote`.`yii_vplancdept` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `d_id` int(11) NOT NULL,
  `cd_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
  
  CREATE TABLE `yiivote`.`yii_vplanuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

  
INSERT INTO `yiivote`.`yii_user` (`id`, `user`, `pwd`, `authKey`, `accessToken`, `nickname`, `thumb`, `email`, `created_at`, `updated_at`) VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'test100key', '100-token', '管理员', 'avatar/1422621856.jpg', 'admin@admin.com', '0', '0');
