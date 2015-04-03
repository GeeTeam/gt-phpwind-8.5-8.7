<?php 

include_once(D_P.'data/bbscache/geetest_config.php');
define('GT_API_SERVER', 'http://api.geetest.com');
define('GT_SDK_VERSION', 'php_2.15.4.1.1');
define("GEETEST_ID", trim($geetest_config['geetest_id']));
define("PRIVATE_KEY", trim($geetest_config['private_key']));
class geetestlib{
	
	function __construct(){
		$this->challenge = "";
	}

	function register() {
		$this->challenge = $this->_send_request("/register.php", array("gt"=>GEETEST_ID));
		if (strlen($this->challenge) != 32) {
			return 0;
		}
		return 1;
	}


	function pw_geetest_widget() {
		$params = array(
			"gt" => GEETEST_ID,
			"challenge" => $this->challenge,
			"product" => "float",
			"sdk" => GT_SDK_VERSION,
		);
		return "<script type='text/javascript' src='".GT_API_SERVER."/get.php?".http_build_query($params)."'></script>";
	}


	function pw_geetest_validate($challenge, $validate, $seccode) {	
		if ( ! $this->_check_validate($challenge, $validate)) {
			return FALSE;
		}
		$query = http_build_query(array("seccode"=>$seccode,"sdk"=>GT_SDK_VERSION));
		$codevalidate = $this->_http_post('api.geetest.com', '/validate.php', $query);
		if (strlen($codevalidate)>0 && $codevalidate==md5($seccode)) {
			return TRUE;
		} else if ($codevalidate == "false"){
			return FALSE;
		} else { 
			return $codevalidate;
		}
	}

	function _check_validate($challenge, $validate) {
		if (strlen($validate) != 32) {
			return FALSE;
		}
		if (md5(PRIVATE_KEY.'geetest'.$challenge) != $validate) {
			return FALSE;
		} 
		return TRUE;
	}

	function _send_request($path, $data, $method="GET") {
		$data['sdk'] = GT_SDK_VERSION;

		if ($method=="GET") {
			$opts = array(
			    'http'=>array(
				    'method'=>"GET",
				    'timeout'=>2,
			    )
		    );
		    $context = stream_context_create($opts);
			$response = file_get_contents(GT_API_SERVER.$path."?".http_build_query($data), false, $context);

		} 
		return $response;
	}
	function _http_post($host,$path,$data,$port = 80){
		$http_request = "POST $path HTTP/1.0\r\n";
		$http_request .= "Host: $host\r\n";
		$http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$http_request .= "Content-Length: " . strlen($data) . "\r\n";
		$http_request .= "\r\n";
		$http_request .= $data;
		$response = '';
		if (($fs = @fsockopen($host, $port, $errno, $errstr, 10)) == false) {
			die ('Could not open socket! ' . $errstr);
		}		
		fwrite($fs, $http_request);
		while (!feof($fs))
			$response .= fgets($fs, 1160);
		fclose($fs);		
		$response = explode("\r\n\r\n", $response, 2);
		return $response[1];
	}
}




