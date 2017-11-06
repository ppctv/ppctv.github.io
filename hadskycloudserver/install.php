<?php
if (!defined('puyuetian'))
	exit('403');

if ($_G['USER']['ID'] == 1) {
	$zds = explode(',', 'qqopenid,wxopenid,weibo_uid,baidu_userid');

	foreach ($zds as $zd) {
		//检测该字段是否存在
		if (!$_G['TABLE']['USER'] -> getColumns($zd)) {
			mysql_query("ALTER TABLE `{$_G['MYSQL']['PREFIX']}user` ADD `{$zd}` varchar(255) null");
		}
	}
	
	
	//建表
	if(!$_G['TABLE']['APP_HADSKYCLOUDSERVER_CLOUDPAY_RECORD']){
		mysql_query("CREATE TABLE `{$_G['MYSQL']['PREFIX']}app_hadskycloudserver_cloudpay_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hs_id` bigint(20) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `rmb` int(11) NOT NULL DEFAULT '0',
  `tiandou` int(11) NOT NULL DEFAULT '0',
  `createtime` int(11) NOT NULL DEFAULT '0',
  `finishtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	}
}
exit();
