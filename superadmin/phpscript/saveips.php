<?php
if (!defined('puyuetian'))
	exit('403');

if (strpos($_POST['ips'], '?') === FALSE)
	file_put_contents($_G['SYSTEM']['PATH'] . '/puyuetian/ips/config.php', '<?php exit("403"); ?>' . $_POST['ips']);

header("Location:index.php?c=app&a=superadmin:index&s=home&t=ips&pkalert=show&alert=" . urlencode('操作成功！') . "&rnd={$_G['RND']}");
