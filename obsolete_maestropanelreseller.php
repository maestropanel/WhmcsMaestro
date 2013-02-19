<?php
//////////////////////////////////////////////////////////////////////////
// Maestro Panel WHMCS API v1.0 by Smyrna Telekom & SunucuHizmeti.Com.TR
// http://www.maestropanel.com/ - MaestroPanel
// http://wiki.maestropanel.com/whmcs-module.ashx Help Page
// http://www.smyrna.com.tr/ & http://www.sunucuhizmeti.com.tr - API Developer
// Maestro Panel WHMCS API v1.0 by Smyrna Telekom (Hosting) Sunucu Hizmeti (Reseller)
///////////////////////////////////////////////////////////////////////////
  function maestropanelreseller_configoptions ()
  {
    $configarray = array ('Domain Plan' => array ('Type' => 'text', 'Size' => '25'), 'Domain Plan Name' => array ('Type' => 'text', 'Size' => '25'));
    return $configarray;
  }
  
    function maestropanelreseller_clientarea ($params)
  {
    $code = '<form action="http://' . $params['serverip'] . ':9715" method="get" target="_blank"><input type="submit" value="Login to MaestroPanel" class="button"></form>';
    return $code;
  }

  function maestropanelreseller_adminlink ($params)
  {
    $code = '<form action="http://' . $params['serverip'] . ':9715" method="get" target="_blank"><input type="submit" value="Maestro Panel"></form>';
    return $code;
  }
  
    function maestropanelreseller_createaccount ($params)
  {
	 
	$query3 = 'UPDATE tblhosting SET domain=\'' . $params['domain'] . '\',username=\'' . $params['domain'] . '\' WHERE id=\'' . $params['accountid'] . '\'';
    $result3 = mysql_query ($query3);
	  $module = 'Reseller/Create';
	  $packet = 'key='.$params['serveraccesshash'].'&username='.$params['domain'].'&password='.$params['password'].'&planAlias='.$params['configoption2'].'&firstname='.$params['clientsdetails']['firstname'].'&lastname='.$params['clientsdetails']['lastname'].'&email='.$params['clientsdetails']['email'].'&activedomainuser=true';
      $retval = maestropanelreseller_connection ($params, $module, $packet);
	 if($retval['RESULT']['CODE'] != 0){
		 $result = $retval['RESULT']['CODE'] . ' - ' . $retval['RESULT']['MESSAGE'];  
	  } else {
		$result = "success";    
	  }

	return $result;
  }
  
      function maestropanelreseller_changepassword ($params)
  {
	  $module = 'Reseller/ChangePassword';
	  $packet = 'key='.$params['serveraccesshash'].'&username='.$params['domain'].'&newpassword='.$params['password'].'';
      $retval = maestropanelreseller_connection ($params, $module, $packet);
	 if($retval['RESULT']['CODE'] != 0){
		 $result = $retval['RESULT']['CODE'] . ' - ' . $retval['RESULT']['MESSAGE'];  
	  } else {
		$result = "success";
	  }

	return $result;
  }
    function maestropanelreseller_terminateaccount ($params)
  {
	  $module = 'Reseller/Stop';
	  $packet = 'key='.$params['serveraccesshash'].'&username='.$params['domain'].'';
      $retval = maestropanelreseller_connection ($params, $module, $packet);
	 if($retval['RESULT']['CODE'] != 0){
		 $result = $retval['RESULT']['CODE'] . ' - ' . $retval['RESULT']['MESSAGE'];  
	  } else {
		$result = "success";    
	  }

	return $result;
  }
      function maestropanelreseller_suspendaccount ($params)
  {
	  $module = 'Reseller/Stop';
	  $packet = 'key='.$params['serveraccesshash'].'&username='.$params['domain'].'';
      $retval = maestropanelreseller_connection ($params, $module, $packet);
	 if($retval['RESULT']['CODE'] != 0){
		 $result = $retval['RESULT']['CODE'] . ' - ' . $retval['RESULT']['MESSAGE'];  
	  } else {
		$result = "success";
	  }
	  
	return $result;
  }
      function maestropanelreseller_unsuspendaccount ($params)
  {
	  $module = 'Reseller/Start';
	  $packet = 'key='.$params['serveraccesshash'].'&username='.$params['domain'].'';
      $retval = maestropanelreseller_connection ($params, $module, $packet);
	 if($retval['RESULT']['CODE'] != 0){
		 $result = $retval['RESULT']['CODE'] . ' - ' . $retval['RESULT']['MESSAGE'];  
	  } else {
		$result = "success";    
	  }

	return $result;
  }
    function maestropanelreseller_connection ($params, $module, $packet)
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
	if($module == 'Reseller/Stop'){
	curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "STOP");
	} 
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $packet);

    $retval = curl_exec ($ch);
    if (curl_errno ($ch))
    {
      if ($debug_output == 'on')
      {
        echo '<textarea rows=2 cols=80>' . curl_errno ($ch) . ' - ' . curl_error ($ch) . '</textarea>';
      }
    }
    curl_close ($ch);
	$res = xmltoarray($retval);
    return $res;
  }
?>