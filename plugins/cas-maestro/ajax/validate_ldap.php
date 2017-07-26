<?php
error_reporting(E_ALL ^ E_NOTICE);


if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$version = intval($_POST['v']);
	$server = $_POST['s'];
	$username = $_POST['u'];
	$password = $_POST['pw'];
	$basedn = $_POST['bdn'];
	$uid = $_POST['uid'];

	if($ds=@ldap_connect($server)) {
		@ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, $version);
		@ldap_set_option($ds, LDAP_OPT_RESTART, TRUE);

		if($r=@ldap_bind($ds,$username,$password)) {  
			$list = @ldap_list($ds, $basedn, 
				"uid=$uid");

			if ($list !== FALSE && isset($uid)){
				$result = @ldap_POST_entries($ds, $list);
				if ($result['count'] > 0){
					echo json_encode(1);
				} else {
					echo json_encode(0);
				}			
			} else {
				echo json_encode(1);
			}
		} else {
			echo json_encode(0);
		}
	} else {
		//No LDAP connection. Return false;
		echo json_encode(0);
	}
}

