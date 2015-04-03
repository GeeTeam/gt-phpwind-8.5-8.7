<?php
!function_exists('adminmsg') && exit('Forbidden');
require_once(H_P. 'lib/geetestpw.php');

if (!@include D_P.'data/bbscache/geetest_config.php') {
	$pw_geetest_core->createGeeTestConfig();
	include D_P.'data/bbscache/geetest_config.php';
}


InitGP(array('action'));

if($action){
	InitGP(array('geetest_config'));
	$config = array(
		'geetest_id' => $geetest_config['geetest_id'],
		'private_key' => $geetest_config['private_key'],
		'geetest_pages' => $geetest_config['geetest_pages'],
		'geetest_user_groups' => $geetest_config['geetest_user_groups'],
	);

	if ($pw_geetest_core->createGeeTestConfig($config)) {
		Showmsg('修改完成', $basename);
	}
} else {
	$geetest_pages[1] = $geetest_config['geetest_pages']['register'] ? 'checked' : '';
	$geetest_pages[2] = $geetest_config['geetest_pages']['login'] ? 'checked' : '';
	$geetest_pages[3] = $geetest_config['geetest_pages']['post'] ? 'checked' : '';
	foreach ($ltitle as $key=>$value) {
		$checked = '';
		if($geetest_config['geetest_user_groups'][$key]){
			$checked = 'checked';
		}
		$num++;
		$htm_tr = $num%4 == 0 ?  '</tr><tr>' : '';
		$usergroup .="<td width='20%'><input type='checkbox' name='geetest_config[geetest_user_groups][$key]' value='1' $checked>$value</td>$htm_tr";
	}
}

include PrintHack('admin');exit;
?>