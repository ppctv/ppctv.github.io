<?php
if (!defined('puyuetian'))
	exit('403');

//云服务js嵌入
$nodes = explode(',', $_G['SET']['APP_HADSKYCLOUDSERVER_NODES']);
if (count($nodes) != 3) {
	$nodes = array('new', 'http', 'east');
}
if ($nodes[0] == 'new') {
	$csurl = $nodes[1] . '://' . $nodes[2] . '.cloudserver.hadsky.com' . '/' . $_G['SYSTEM']['DOMAIN'] . '.js?v=' . HADSKY_VERSION . '&rnd=' . $_G['RND'];
} else {
	$csurl = $nodes[1] . '://' . $nodes[2] . '.hadsky.com/index.php?c=app&a=zhanzhang:index3&s=tongji&domain=' . $_G['SYSTEM']['DOMAIN'];
}

//$_G['SET']['EMBED_FOOT'] .= '<script>$(function(){$("head").append(\'<script async src="' . $csurl . '"><\\/script>\')})</script>';
$_G['SET']['EMBED_FOOT'] .= '
<script>
	$(function(){
		$.getScript("' . $csurl . '",function(response,status){
			if(status=="success"){
				//console.log("");
			}else{
				console.log(\'云服务加载失败：status = "\' + status + \'"\');
			}
		});
	});
</script>
';
unset($csurl, $nodes);

//用qq号登录
if (!$_G['USER']['ID'] && $_SESSION['APP_HADSKYCLOUDSERVER_QQLOGIN_OPENID']) {
	if ($_G['SET']['APP_HADSKYCLOUDSERVER_QQLOGIN_OPENREG']) {
		$_G['SET']['OPENREG'] = 1;
	}
	$regarray['qqopenid'] = $_SESSION['APP_HADSKYCLOUDSERVER_QQLOGIN_OPENID'];
	$_G['TEMP']['REGNICKNAME'] = $_SESSION['APP_HADSKYCLOUDSERVER_QQLOGIN_NICKNAME'];
}

//用微博号登录
if (!$_G['USER']['ID'] && $_SESSION['APP_HADSKYCLOUDSERVER_WEIBOLOGIN_UID']) {
	if ($_G['SET']['APP_HADSKYCLOUDSERVER_WEIBOLOGIN_OPENREG']) {
		$_G['SET']['OPENREG'] = 1;
	}
	$regarray['weibo_uid'] = $_SESSION['APP_HADSKYCLOUDSERVER_WEIBOLOGIN_UID'];
	$_G['TEMP']['REGNICKNAME'] = 'weibo_' . $regarray['weibo_uid'];
}

//用百度号登录
if (!$_G['USER']['ID'] && $_SESSION['APP_HADSKYCLOUDSERVER_BAIDULOGIN_USERID']) {
	if ($_G['SET']['APP_HADSKYCLOUDSERVER_BAIDULOGIN_OPENREG']) {
		$_G['SET']['OPENREG'] = 1;
	}
	$regarray['baidu_userid'] = $_SESSION['APP_HADSKYCLOUDSERVER_BAIDULOGIN_USERID'];
	$_G['TEMP']['REGNICKNAME'] = 'baidu_' . $regarray['baidu_userid'];
}

//天豆兑换数量前端化
$_G['SET']['EMBED_HEADMARKS'] .= "
<script>
var HADSKY_VERSION='" . HADSKY_VERSION . "';
var \$app_hadskycloudserver_tiandouduihuanshu=parseInt('{$_G['SET']['APP_HADSKYCLOUDSERVER_TIANDOUDUIHUANSHU']}')||0;
var \$app_hadskycloudserver_tiandouname='{$_G['SET']['TIANDOUNAME']}';
</script>
";
