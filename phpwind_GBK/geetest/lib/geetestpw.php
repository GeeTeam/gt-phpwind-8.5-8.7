<?php 
/**
 * �������޸� /data/bbscache/geetest_config.php �ĺ���
 */
include_once('geetestlib.php');

$pw_geetest_core = new pwGeeTest(); //phpwind geetest ���ı������������е�������

class pwGeeTest {

	function createGeeTestConfig($config = array()) {
		if (empty($config)) {
			$config = array(
				'geetest_id' => '',
				'private_key' => '',
				'geetest_pages' => array(
					'1' => '�û�ע��',
					'2' => '�û���¼',
					'3' => '�����ظ�'
				)
			);
		}

		$config_str = "<?php\r\n\r\n \$geetest_config = ".pw_var_export($config).";\r\n";
		writeover(D_P.'/data/bbscache/geetest_config.php', $config_str);

		return 1;
	}

	/**
	 * ���ݵ�ǰҳ�淵�� geetest �Ĵ��룬���򷵻ؿմ�
	 * @param  [type] $page [description]
	 * @return [type]       [description]
	 */
	function generateGeeTestHtml($page) {
		global $geetest_config, $groupid;
		$geetestlib = new geetestlib;
		if ($geetestlib->register($geetest_config['geetest_id'])) {

		$gt_pages_cfg = $geetest_config['geetest_pages'];
		$gt_groups_cfg = $geetest_config['geetest_user_groups'];
		switch ($page) {
			case 'register':
				$bDisplay = $gt_pages_cfg['register'] == 1;
				break;
			
			case 'login':
				$bDisplay = $gt_pages_cfg['login'] == 1;
				break;			
			
			case 'post':				
				$bDisplay = $gt_pages_cfg['login'] == 1 || ($gt_pages_cfg['post'] == 1);
				break;						
			
			case 'read':				
				$bDisplay = $gt_pages_cfg['post'] == 1;
				break;						
			
			default:				
				$bDisplay = FALSE;
				break;
		}
			return $bDisplay ? $geetestlib->pw_geetest_widget() : '';
		}else{
			return;
		}
	}
	
	function doGeeTestValidate($page) {
		global $geetest_config;
		$geetestlib = new geetestlib;
		if (!empty($geetest_config['geetest_pages'][$page])) {
			if (!$geetestlib->pw_geetest_validate(GetGP('geetest_challenge'), GetGP('geetest_validate'), GetGP('geetest_seccode'))) {
				Showmsg('����ͨ��������֤');
			}
		}	
	}
}