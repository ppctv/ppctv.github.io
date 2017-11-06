<?php
if (!defined('puyuetian'))
	exit('403');

if (!$_G['USER']['ID'] || ($_G['USER']['ID'] != 1 && !InArray($_G['SET']['APP_SUPERADMIN_ADMINUIDS'], $_G['USER']['ID']))) {
	header('Location:index.php?c=login&referer=' . urlencode($_G['SYSTEM']['LOCATION']));
	exit();
}

if ($_G['SET']['APP_SUPERADMIN_PASSWORD']) {
	//开启了安全验证
	$_POST['hadsky_superadmin_password'] && $_SESSION['APP_SUPERADMIN_PASSWORD'] = $_POST['hadsky_superadmin_password'];
	if ($_SESSION['APP_SUPERADMIN_PASSWORD'] != $_G['SET']['APP_SUPERADMIN_PASSWORD']) {
		$_G['SET']['WEBTITLE'] = '登录HadSky后台管理系统';
		$_G['HTMLCODE']['MAIN'] = template('superadmin:inputpassword', TRUE);
		template($_G['TEMPLATE']['MAIN']);
		exit();
	}
}

$wd = date('H:i:s') . "\t{$_G['USER']['ID']}\t{$_G['SYSTEM']['LOCATION']}\r\n";
$fp = "{$_G['SYSTEM']['PATH']}app/superadmin/logs/" . date('Y-m-d') . '_' . substr(md5($_G['SET']['KEY']), 0, 7) . '.txt';
if (!file_exists($fp)) {
	file_put_contents($fp, "OperatingTime\tUID\tRequestURI\r\n", FILE_APPEND);
}
//记录日志
file_put_contents($fp, $wd, FILE_APPEND);
unset($wd, $fp);

$_G['USER']['ID'] = 1;

//安全版本检测码
$nowversion = explode('.', HADSKY_VERSION);
$nowversion2 = '';
foreach ($nowversion as $value) {
	if ($value < 10) {
		$value = '0' . $value;
	}
	$nowversion2 .= $value;
}
$nowversion = $nowversion2;
//云通讯检验码生成
$yccc = TRUE;
$YCCP = $_G['SYSTEM']['PATH'] . '/yuncheckcode.htm';
if (file_exists($YCCP)) {
	$lscc = json_decode(file_get_contents($YCCP), TRUE);
	if ($lscc['deadline'] > time()) {
		$YUNCHECKCODE = $lscc['yuncheckcode'];
		$yccc = FALSE;
	}
}
if ($yccc) {
	$YUNCHECKCODE = CreateRandomString(16);
	file_put_contents($YCCP, json_encode(array('yuncheckcode' => $YUNCHECKCODE, 'deadline' => (time() + 60))));
}

$_G['TEMPLATE']['HEAD'] = 'superadmin:head';
$_G['TEMPLATE']['BODY'] = 'superadmin:body';
$_G['TEMPLATE']['FOOT'] = 'superadmin:foot';

$currentscriptname = strtolower(EqualReturn($_G['GET']['S'], NULL, 'home'));
$PP = $_G['SYSTEM']['PATH'] . '/app/superadmin/phpscript/' . $currentscriptname . '.php';

if (file_exists($PP)) {
	if (InArray('save,delete,savecache', $currentscriptname)) {
		if ($_G['CHKCSRFVAL'] != $_POST['chkcsrfval'] && $_G['CHKCSRFVAL'] != $_GET['chkcsrfval'] && $_G['SET']['CHKCSRF']) {
			exit('Has been blocked by HadSky security mechanism, the reason: suspected CSRF attacks');
		}
	}
	require $PP;
} else {
	RunError("\"{$PP}\" doesn't exist");
}
