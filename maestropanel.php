<?php
//////////////////////////////////////////////////////////////////////////
// Maestro Panel WHMCS API v1.1 by Smyrna Telekom
// http://www.maestropanel.com/ - MaestroPanel
// http://wiki.maestropanel.com/whmcs-module.ashx Help Page
// http://www.smyrna.com.tr/ - API Developer
// Maestro Panel WHMCS API v2.1 by Smyrna Telekom
///////////////////////////////////////////////////////////////////////////
	function maestropanel_configoptions ()
	{
		$configarray = array ('Domain Plan' => array ('Type' => 'text', 'Size' => '25'), 'Domain Plan Name' => array ('Type' => 'text', 'Size' => '25'));
		return $configarray;
	}
  
	function maestropanel_clientarea ($params)
	{
		$code = '<form action="http://' . $params['serverip'] . ':9715/Auth/SignOn" method="post" target="_blank"><input type="hidden" name="username" value="' . $params['username'] . '"><input type="hidden" name="password" value="' . $params['password'] . '"><input type="submit" value="Login to MaestroPanel" class="button"></form>';
		return $code;
	}

	function maestropanel_adminlink ($params)
	{
		$code = '<form action="http://' . $params['serverip'] . ':9715/Auth/SignOn" method="get" target="_blank"><input type="hidden" name="username" value="' . $params['serverusername'] . '"><input type="hidden" name="password" value="' . $params['serverpassword'] . '"><input type="submit" value="MaestroPanel"></form>';
		return $code;
	}
  
	function maestropanel_createaccount ($params)
	{
		$query3 = 'UPDATE tblhosting SET domain=\'' . $params['domain'] . '\',username=\'' . $params['domain'] . '\' WHERE id=\'' . $params['accountid'] . '\'';
		$result3 = mysql_query ($query3);

		if($params['type'] == 'reselleraccount')
		{	
			$module = 'Reseller/Create';
			$packet = 'key='.$params['serveraccesshash'].'&name='.$params['domain'].'&planAlias='.$params['configoption2'].'&username='.$params['domain'].'&password='.$params['password'].'&firstname='.$params['clientsdetails']['firstname'].'&lastname='.$params['clientsdetails']['lastname'].'&email='.$params['clientsdetails']['email'].'&activedomainuser=true';
		} 
		else 
		{
			$module = 'Domain/Create';
			$packet = 'key='.$params['serveraccesshash'].'&name='.$params['domain'].'&planAlias='.$params['configoption2'].'&username='.$params['domain'].'&password='.$params['password'].'&firstname='.$params['clientsdetails']['firstname'].'&lastname='.$params['clientsdetails']['lastname'].'&email='.$params['clientsdetails']['email'].'&activedomainuser=true';
		}

		$retval = maestropanel_connection ($params, $module, $packet);
		
		if($retval['RESULT']['ERRORCODE'] != 0)
		{
			$result = $retval['RESULT']['MESSAGE'] .' ('. $retval['RESULT']['ERRORCODE'] .')';
		}
		else 
		{
			return "success";
		}

		return $result;
	}
  
	function maestropanel_changepassword ($params)
	{
		if($params['type'] == 'reselleraccount')
		{
			$module = 'Reseller/ChangePassword'; 
			$packet = 'key='.$params['serveraccesshash'].'&username='.$params['domain'].'&newpassword='.$params['password'].'';
		}		
		else 
		{
			$module = 'Domain/Password';
			$packet = 'key='.$params['serveraccesshash'].'&name='.$params['domain'].'&newpassword='.$params['password'].'';
		}
				
		$retval = maestropanel_connection ($params, $module, $packet);
		
		if($retval['RESULT']['ERRORCODE'] != 0)
		{
			$result = $retval['RESULT']['MESSAGE'] .' ('. $retval['RESULT']['ERRORCODE'] .')';
		}
		else 
		{
			return "success";
		}

		return $result;
	}

	function maestropanel_terminateaccount ($params)
	{
		if($params['type'] == 'reselleraccount')
		{
			$module = 'Reseller/Stop'; 
			$packet = 'key='.$params['serveraccesshash'].'&username='.$params['domain'].'';
		}
		else 
		{
			$module = 'Domain/Delete';
			$packet = 'key='.$params['serveraccesshash'].'&name='.$params['domain'].'';
		}
		
		$retval = maestropanel_connection ($params, $module, $packet);

		if($retval['RESULT']['ERRORCODE'] != 0)
		{
			$result = $retval['RESULT']['MESSAGE'] .' ('. $retval['RESULT']['ERRORCODE'] .')';
		}
		else 
		{
			return "success";
		}

		return $result;
	}
	
	function maestropanel_suspendaccount ($params)
	{
		if($params['type'] == 'reselleraccount')
		{
			$module = 'Reseller/Stop'; 
			$packet = 'key='.$params['serveraccesshash'].'&username='.$params['domain'].'';
		}
		else 
		{
			$module = 'Domain/Stop';
			$packet = 'key='.$params['serveraccesshash'].'&name='.$params['domain'].'';
		}
		
		
		$retval = maestropanel_connection ($params, $module, $packet);
		
		if($retval['RESULT']['ERRORCODE'] != 0)
		{
			$result = $retval['RESULT']['MESSAGE'] .' ('. $retval['RESULT']['ERRORCODE'] .')';
		}
		else 
		{
			return "success";
		}

		return $result;
	}
	
	
	function maestropanel_unsuspendaccount ($params)
	{
		if($params['type'] == 'reselleraccount')
		{
			$module = 'Reseller/Start'; 
			$packet = 'key='.$params['serveraccesshash'].'&username='.$params['domain'].'';
		}
		else 
		{
			$module = 'Domain/Start';
			$packet = 'key='.$params['serveraccesshash'].'&name='.$params['domain'].'';
		}
				
		$retval = maestropanel_connection ($params, $module, $packet);
		
		if($retval['RESULT']['ERRORCODE'] != 0)
		{
			$result = $retval['RESULT']['MESSAGE'] .' ('. $retval['RESULT']['ERRORCODE'] .')';
		}
		else 
		{
			return "success";
		}

		return $result;
	}
	
	function maestropanel_connection ($params, $module, $packet)
	{
		global $debug_output;
		global $clientid;
		$url = 'http://' . $params['serverip'] . ':9715/Api/'.$module;
		$ch = curl_init ();
		
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 15);
		
		if($module == 'Domain/Delete')
		{
			curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		}
		
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $packet);

		$retval = curl_exec ($ch);
		
		if (curl_errno ($ch))
		{
			echo '<textarea rows=2 cols=80>' . curl_errno ($ch) . ' - ' . curl_error ($ch) . '</textarea>';			
		}
		
		curl_close ($ch);		
		
		$res = xmltoarray($retval);
		
		if(!array_key_exists('RESULT', $res))
		{
			$res = Array("RESULT" => Array("STATUSCODE" => 500, "ERRORCODE" => 9, "MESSAGE" => "Server Unreachable ". $params['serverip']));
		}	
		
		logModuleCall( "maestropanel", $url, $packet, $retval, $res );
		
		return $res;
	}
?>